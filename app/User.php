<?php

namespace App;

use App\Country;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Kalnoy\Nestedset\NodeTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    // protected $appends = ['address', 'company_address', 'default_locale', 'is_std', 'group_sale', 'has_verified_sale', 'commission_received', 'group_sale_needed', 'direct_descendants_count', 'direct_referrer_needed', 'descendants_count', 'commission_received_std', 'unpaid_commission', 'unpaid_commission_std', 'transaction_start', 'transaction_end'];

    // protected $appends = ['address'];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    // protected $with = ['package', 'addresses', 'contacts'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function package()
    {
        return $this->belongsTo('App\Package');
    }

    public function ewallet()
    {
        return $this->hasOne('App\Ewallet');
    }

    public function redemptions()
    {
        return $this->hasMany('App\Redemption');
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function accounts()
    {
        return $this->hasMany('App\Account');
    }

    public function getPersonalAddressAttribute()
    {
        return $this->addresses()->where('type', Address::PERSONAL())->first();
    }

    public function getCompanyAddressAttribute()
    {
        return $this->addresses()->where('type', Address::COMPANY())->first();
    }

    public function setCountryIdAttribute($value)
    {
        $shouldRegenerate = $this->country_id != $value;

        $this->attributes['country_id'] = $value;
        
        if($shouldRegenerate) {
            $country = Country::find($value);
            // $this->attributes['referral_code'] = $this->generateReferralCode($country);
        }
    }

    public function getDefaultLocaleAttribute()
    {
        return $this->country->default_locale;
    }

    public function getAddressAttribute()
    {
        if($this->personal_address) {
            return $this->personal_address->display;
        }

        return '';
    }

    public function getCompanyAddressDisplayAttribute()
    {
        if($this->personal_address) {
            return $this->company_address->display;
        }

        return '';
    }

    public function getContactNumberAttribute()
    {
        return $this->personal_address ? $this->personal_address->phone : "";
    }

    public function getIsStdAttribute()
    {
        return $this->country->name == "China";
    }


    public function getGroupSaleAttribute()
    {
        return $this->descendants()->sum('tree_count') + $this->tree_count;
    }


    public function generateReferralCode($country)
    {
        $code = strtoupper($country->code) . rand(pow(10, 4), pow(10, 5)-1);

        if(User::where('referral_code', $code)->count() > 0)
            $code = $this->generateReferralCode($country);

        return $code;
    }

    public function getCommisionPercentageAttribute()
    {

        switch($this->user_level) {
            case 1:
                $percentage = 8;
                break;
            case 2: 
                $percentage = 10;
                break;
            case 3:
                $percentage = 15;
                break;
            case 4:
                $percentage = 20;
                break;
            default:
                $percentage = 0;
        }
        
        return $percentage / 100;
    }

    public function getHasVerifiedSaleAttribute()
    {
        return $this->purchases()->where('is_verified', 1)->count() > 0;
    }

    public function getDirectReferrerNeededAttribute()
    {
        $children = 20;

        switch($this->user_level) {
            case 2:
                $children = 10;
                break;
            case 1:
                $children = 3;
                break;
        }

        return $children;
    }

    public function getGroupSaleNeededAttribute()
    {
        $sale = 0;

        switch($this->user_level) {
            case 2:
                $sale = 100;
                break;
            case 3:
                $sale = 200;
                break;       
        }

        return $sale;
    }

    // Adjust user level
    public function adjust_level()
    {
        $number_of_children = $this->children()->where('tree_count', '>', 0)->count();
        $number_of_group_sales = $this->group_sale;

        $user_level = 1;

        if($number_of_group_sales >= 200 && $number_of_children >= 20)
        {
            $user_level = 4;
        }
        else if($number_of_group_sales >= 100 && $number_of_children >= 10)
        {
            $user_level = 3;
        }
        else if($number_of_children >= 3)
        {
            $user_level = 2;
        }

        if($this->user_level < $user_level)
            $this->update(['user_level' => $user_level]);
    }

    public function getCommissionReceivedAttribute()
    {
        return $this->transactions()->where("is_std", false)->sum("amount");
    }

    public function getCommissionReceivedStdAttribute()
    {
        return $this->transactions()->where("is_std", true)->sum("amount_std");
    }

    public function getDirectDescendantsCountAttribute()
    {
        return $this->children()->where('tree_count', '>', 0)->count();
    }

    public function getDescendantsCountAttribute()
    {
        return $this->descendants()->count();
    }

    public function getUnpaidCommissionAttribute()
    {
        return $this->transactions()->where("is_std", false)->where("payout_status", false)->sum("amount");
    }

    public function getUnpaidCommissionStdAttribute()
    {
        return $this->transactions()->where("is_std", true)->where("payout_status", false)->sum("amount");
    }

    public function getTransactionStartAttribute()
    {
        return $this->transactions()->min("date");
    }

    public function getTransactionEndAttribute()
    {
        return $this->transactions()->max("date");
    }

    public function getHasPurchaseAttribute() 
    {
        if($this->purchases()->first())
            return true;
        else
            return false;
    }

    // Reset password
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($this, App::getLocale(), $token));
    }
}
