<?php

namespace App;

use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    protected $appends = ['user_name'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function packages()
    {
    	return $this->belongsToMany('App\Package')->withPivot('amount', 'total_price', 'total_price_std')->withTimestamps();
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function verify()
    {
    	$this->update(['status' => 'complete', 'is_verified' => true]);

        $tree_count = $this->user->tree_count;

        $this->user()->update([ 'tree_count' => $tree_count + $this->packages->sum(function($package){ return $package->pivot->amount * $package->tree_count; }) ]);

        $this->pay_and_roll_commission_upwards($this->user);

        foreach(User::ancestorsAndSelf($this->user_id) as $user)
        {
            $user->adjust_level();
        }

        return $this;
    }

    public function pay_and_roll_commission_upwards($user, $paid_percentage = 0.0)
    {
        if(!is_null($user->parent_id)) {
            $parent = $user->parent;

            $percentage = $parent->commision_percentage > $paid_percentage ? $parent->commision_percentage - $paid_percentage : 0.0;

            // dd($this->total_price);
            if($percentage > 0)
            {
                $transaction = Transaction::create($user->parent, 
                                                    Transaction::TYPE_COMMISION(), 
                                                    Transaction::DESCRIPTION_TREE_PURCHASE(), 
                                                    $this->total_price * $percentage, 
                                                    $this->total_price_std * 
                                                    $percentage, 
                                                    $this->is_std, 
                                                    $this->created_at,
                                                    $percentage);

                $transaction->update(['purchase_id' => $this->id, 'target_id' => $this->user_id]);

                $paid_percentage +=  $percentage;

            }

            $this->pay_and_roll_commission_upwards($parent, $paid_percentage);
        }
    }

    public function reject($note)
    {
        $this->update(['status' => 'rejected']);

        // Notify user about this
    }
}
