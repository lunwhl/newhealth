<?php

namespace App\Http\Controllers;

use App\Address;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function setLanguage()
    {
        App::setLocale(request()->lang);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user; 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
       $validated = request()->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'country_id' => 'required|exists:countries,id',
            'company_country_id' => 'sometimes|required|exists:countries,id',
            'address_line_1' => 'required',
            'postcode' => 'required',
            'nationality' => 'required',
            'phone' => 'required',
            'password' => 'sometimes|required|confirmed',
            'identification' => 'required',
            'company_name' => 'sometimes|required',
            'company_business_registration' => 'sometimes|required',
            'company_type' => 'sometimes|required',
            'company_incorporation_place' => 'sometimes|required',
            'company_incorporation_date' => 'sometimes|required',
            'company_address_line_1' => 'sometimes|required',
            'company_postcode' => 'sometimes|required',
            'company_phone' => 'sometimes|required',
            'company_email' => 'sometimes|required',
            'bank_name' => 'required',
            'bank_address' => 'required',
            'account_type' => 'required',
            'account_no' => 'required',
            'beneficiary_name' => 'required'
        ]);

        $user->update([
            'email' => request()->email,
            'name' => request()->name,
            'country_id' => request()->country_id,
            'identification' => request()->identification,
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
            'beneficiary_identification' => request()->beneficiary_identification
        ]);

        if(request()->has('isChangePassword'))
        {
            $user->update([ 'password' => bcrypt(request()->password) ]);
        }

        $user->addresses()->updateOrCreate(['type' => Address::PERSONAL()],
            [
            'line_1' => request()->address_line_1,
            'line_2' => request()->address_line_2,
            'country_id' => request()->country_id,
            'postcode' => request()->postcode,
            'phone' => request()->phone,
            "type" => Address::PERSONAL()
            ]
        );

        if(isset($validated['company_address_line_1']))
        {
            $user->addresses()->updateOrCreate(["type" => Address::COMPANY()],
                [
                'line_1' => request()->company_address_line_1,
                'line_2' => request()->company_address_line_2,
                'country_id' => request()->company_country_id,
                'postcode' => request()->company_postcode,
                'phone' => request()->company_phone,
                "type" => Address::COMPANY()
            ]);
        }

        $user->contacts()->delete();
        if(isset(request()->personnels))
        {
            foreach(json_decode(request()->personnels) as $personnel)
            {
                $user->contacts()->create([
                    'name' => $personnel->name,
                    'designation' => $personnel->designation,
                    'email' => $personnel->email,
                    'phone' => $personnel->phone
                ]);
            }
        }

        return json_encode(['message' => 'user.profile_update_success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function showCompanyContacts(User $user)
    {
        return $user->contacts;
    }

    public function getTree(User $user)
    {   
        $users = User::descendantsAndSelf($user->id);

        if(!is_null($user->parent))
            $users->push($user->parent);

        return $users->toTree();
    }
}
