<?php

namespace App\Http\Controllers;

use App\Address;
use App\Notifications\RegisterSuccess;
use App\User;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
    	return json_encode($request->user()->load('addresses')->append(['address', 'company_address', 'default_locale', 'is_std', 'group_sale', 'has_verified_sale', 'commission_received', 'group_sale_needed', 'direct_descendants_count', 'direct_referrer_needed', 'descendants_count', 'commission_received_std', 'unpaid_commission', 'unpaid_commission_std', 'transaction_start', 'transaction_end', 'has_purchase']));
    }

    public function show()
    {
        return view('complete');
    }

    public function update()
    {
    	$validated = request()->validate([
    		'email' => 'required|email',
    		'country_id' => 'required|exists:countries,id',
            'company_country_id' => 'sometimes|required|exists:countries,id',
            'address_line_1' => 'required',
            'postcode' => 'required',
            'nationality' => 'required',
            'phone' => 'required',
            'password' => 'sometimes|required|confirmed',
            'identification' => 'required',
            // 'company_name' => 'sometimes|required',
            // 'company_business_registration' => 'sometimes|required',
            // 'company_type' => 'sometimes|required',
            // 'company_incorporation_place' => 'sometimes|required',
            // 'company_incorporation_date' => 'sometimes|required',
            // 'company_address_line_1' => 'sometimes|required',
            // 'company_postcode' => 'sometimes|required',
            // 'company_phone' => 'sometimes|required',
            // 'company_email' => 'sometimes|required',
            'bank_name' => 'required',
            'bank_address' => 'required',
            'account_type' => 'required',
            'account_no' => 'required',
            // 'beneficiary_name' => 'required',
            // 'beneficiary_contact' => 'required',
            // 'beneficiary_address' => 'required|max:255',
            // 'beneficiary_identification' => 'required',
            'terms' => 'accepted',
            'id_type' => 'required'
    	]);

        $account = Account::create([
            'user_id' => auth()->user()->id,
            'referral_code' => request()->country_id,
            ]);

        $referrer = null;

    	if(request()->referrer_code && request()->referrer_code !== "")
    	{
            $referrer = Account::where('referral_code', request()->referrer_code)->first();  
    	} 

        $referrer ? $account->appendToNode($referrer)->save() : $account->saveAsRoot();
            
    	auth()->user()->update([
            'email' => request()->email,
            'name' => request()->name,
            'country_id' => request()->country_id,
            'identification' => request()->identification,
            'id_type' => request()->has('id_type') ? request()->id_type : 'nric',
            'nationality' => request()->nationality,
            'gender' => request()->gender,
            'company_name' => request()->company_name,
            'company_business_registration' => request()->company_business_registration,
            'company_incorporation_place' => request()->company_incorporation_place,
            'company_incorporation_date' => request()->company_incorporation_date,
            'company_regulatory_name' => request()->company_regulatory_name,
            'company_type' => request()->company_type,
            'company_email' => request()->company_email,
            'bank_name' => request()->bank_name,
            'bank_swift' => request()->bank_swift,
            'bank_address' => request()->bank_address,
            'account_type' => request()->account_type,
            'account_no' => request()->account_no,
            'beneficiary_name' => request()->beneficiary_name,
            'beneficiary_identification' => request()->beneficiary_identification,
            'beneficiary_address' => request()->beneficiary_address,
            'beneficiary_contact' => request()->beneficiary_contact,
            'referral_code' => request()->country_id,
        ]);

        if(request()->has('password'))
        {
            auth()->user()->update([ 'password' => bcrypt(request()->password) ]);
        }

        auth()->user()->addresses()->updateOrCreate(
            [
                'type' => Address::PERSONAL(),
                'user_id' => auth()->id()
            ],
            [
            'line_1' => request()->address_line_1,
            'line_2' => request()->address_line_2,
            'country_id' => request()->country_id,
            'postcode' => request()->postcode,
            'phone' => request()->phone,
            'type' => Address::PERSONAL()
        ]);

        if(isset($validated['company_address_line_1']))
        {
            auth()->user()->addresses()->updateOrCreate(
                [
                'type' => Address::PERSONAL(),
                'user_id' => auth()->id()
                ],
                [
                'line_1' => request()->company_address_line_1,
                'line_2' => request()->company_address_line_2,
                'country_id' => request()->company_country_id,
                'postcode' => request()->company_postcode,
                'phone' => request()->company_phone,
                'type' => Address::COMPANY()
            ]);
        }

        if(isset(request()->personnel))
        {
            foreach(request()->personnel as $personnel)
            {
                auth()->user()->contacts()->create([
                    'name' => $personnel[0],
                    'designation' => $personnel[1],
                    'email' => $personnel[2],
                    'phone' => $personnel[3]
                ]);
            }
        }

        // Send email to user to notify register success
        // auth()->user()->notify( new RegisterSuccess(auth()->user(), App::getLocale()) );
                       

    	return redirect('home#/purchases?new=1&lang=' . App::getLocale());
    }
}
