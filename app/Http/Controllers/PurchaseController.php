<?php

namespace App\Http\Controllers;

use App\Package;
use App\Purchase;
use App\User;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(User $user)
    {
        return Controller::VueTableListResult($user->purchases()->with('packages', 'payment')
                                                    ->select('purchases.id as id',
                                                            'payment_id',
                                                            'purchases.created_at as created_at',
                                                            'purchases.total_price as total_price',
                                                            'purchases.total_price_std as total_price_std',
                                                            'purchases.is_std as is_std',
                                                            'purchases.status as status',
                                                            'user_id',
                                                            'users.name as user_name')
                                                    ->leftJoin('users', 'users.id', '=', 'user_id')
                                                );
    }

    public function store()
    {
    	$packages = json_decode(request()->packages);
        $processed_packages = [];
    	foreach ($packages as $key => $package) {
            if($package->amount > 0) {
                $db_package = Package::find($package->id);
                $price = $db_package->price_promotion > 0 ? $db_package->price_promotion : $db_package->price;
                $price_std = $db_package->price_std_promotion > 0 ? $db_package->price_std_promotion : $db_package->price_std;
                $processed_packages[$package->id]['total_price'] = $price * $package->amount;
                $processed_packages[$package->id]['total_price_std'] = $price_std * $package->amount;
                $processed_packages[$package->id]['amount'] = $package->amount;
            }
    	}

    	$packages = collect($processed_packages);

    	$total_price = $packages->sum(function($package){
    		return $package['total_price'];
    	});

        $user = User::find(request()->user_id);

        $total_price_std = $packages->sum(function($package){
            return $package['total_price_std'];
        });

    	$purchase = Purchase::create([
    		'user_id' => request()->user_id,
    		'total_price' => $total_price,
            'total_price_std' => $total_price_std,
            'is_std' => $user->is_std,
            'created_at' => request()->purchase_date
    	]);

    	$purchase->packages()->sync($packages);

    	return json_encode(['message' => 'purchase.success', 'purchase' => Purchase::with('packages')->find($purchase->id)]);
    }

    public function update(Purchase $purchase)
    {
        $packages = json_decode(request()->packages);
        $processed_packages = [];
        foreach ($packages as $key => $package) {
            if($package->amount > 0) {
                $db_package = Package::find($package->id);
                $price = $db_package->price_promotion > 0 ? $db_package->price_promotion : $db_package->price;
                $price_std = $db_package->price_std_promotion > 0 ? $db_package->price_std_promotion : $db_package->price_std;
                $processed_packages[$package->id]['total_price'] = $price * $package->amount;
                $processed_packages[$package->id]['total_price_std'] = $price_std * $package->amount;
                $processed_packages[$package->id]['amount'] = $package->amount;
            }
        }

        $packages = collect($processed_packages);

        $total_price = $packages->sum(function($package){
            return $package['total_price'];
        });

        $total_price_std = $packages->sum(function($package){
            return $package['total_price_std'];
        });

        $purchase->update([
            'total_price' => $total_price,
            'total_price_std' => $total_price_std,
            'created_at' => request()->purchase_date
        ]);

        $purchase->packages()->sync($packages);

        return json_encode(['message' => 'purchase.updated', 'purchase' => Purchase::with('packages')->find($purchase->id)]);
    }

    public function verify(Purchase $purchase)
    { 
        return $purchase->verify();      
    }
}
