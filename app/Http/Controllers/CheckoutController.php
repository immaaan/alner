<?php

namespace App\Http\Controllers;

use App\Koinpack_customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Koinpack_payment;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Alert;
use App\Koinpack_shopping_cart;
use App\Koinpack_voucher;
use App\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{

    public function store($name, $cashback, $phone, $address, $notes, $shipping, $total, $vc)
    {
        // dd($vc);
        $voucher = Koinpack_voucher::where('title', $vc)->first();
        // dd($total - $voucher->price);

        try {
            if ($cashback > 0) {
                $carts = Koinpack_shopping_cart::with([
                    'product', 'customer', 'customer_full'
                ])
                    ->whereHas('product')
                    ->whereHas('customer_full')
                    ->where('users_id', Auth::user()->id)
                    ->get();
    
                $emptyBottles = Koinpack_shopping_cart::with([
                        'empetybottle'
                    ])
                        ->whereHas('empetybottle')
                        ->where('users_id', Auth::user()->id)
                        ->get();
                
                //cashback
                // $customer= Users::find(Auth::user()->id);
                // $customer->cashback += $cashback;
                // $customer->save();

                $products_id = [];
                $emptyBottles_id = [];

                foreach ($carts as $cart) {
                    $get = $cart->product->price * $cart->qty;
    
                    $products_id[] = $cart->product->id.'-'.$cart->qty.'-'.$get;
                    $cart->delete();
                }

                foreach ($emptyBottles as $emptyBottle) {
                    $sumTotalBottle = $emptyBottle->qty * $emptyBottle->empetybottle->price;
                    
                    // id emptybottle - qty - total
                    $emptyBottles_id[] = $emptyBottle->empetybottle->id.'-'.$emptyBottle->qty.'-'.$sumTotalBottle;
                    $emptyBottle->delete();
                }   
    
                $price = $total;
                $external_id = Str::random(10);

                if ($voucher != null){
                    $item = Koinpack_payment::create([
                        "external_id"    => $external_id,
                        "payment_chanel" => 'Cashback',
                        "users_id"          => Auth::user()->id,
                        "price"          => $price - $voucher->price ?? 0,
                        "status"          => 'PENDING',
                        // 'payment_link' => $response->invoice_url,
                        'products_id' => collect($products_id),
                        'emptyBottles_id' => collect($emptyBottles_id),
                        'receiver' => $name,
                        'phone' => $phone,
                        'address' => $address,
                        'notes' => $notes,
                        'shipping' => $shipping,
                        // 'vc' => $vc,
                    ]);
                    
                }else{
                    $item = Koinpack_payment::create([
                        "external_id"    => $external_id,
                        "payment_chanel" => 'Cashback',
                        "users_id"          => Auth::user()->id,
                        "price"          => $price,
                        "status"          => 'PENDING',
                        // 'payment_link' => $response->invoice_url,
                        'products_id' => collect($products_id),
                        'emptyBottles_id' => collect($emptyBottles_id),
                        'receiver' => $name,
                        'phone' => $phone,
                        'address' => $address,
                        'notes' => $notes,
                        'shipping' => $shipping,
                        // 'vc' => $vc,
                    ]);

                }
                                
                // Alert::success('Transaction Created Successfully');        
                return redirect()->route('success',$item->id)->with('message', 'Checkout Success!');
                
            }else {
                $carts = Koinpack_shopping_cart::with([
                    'product', 'customer', 'customer_full'
                ])
                    ->whereHas('product')
                    ->whereHas('customer_full')
                    ->where('users_id', Auth::user()->id)
                    ->get();
    
                $emptyBottles = Koinpack_shopping_cart::with([
                        'empetybottle'
                    ])
                        ->whereHas('empetybottle')
                        ->where('users_id', Auth::user()->id)
                        ->get();
    
                
                $products_id = [];
                $emptyBottles_id = [];

                foreach ($carts as $cart) {
                    $get = $cart->product->price * $cart->qty;
    
                    $products_id[] = $cart->product->id.'-'.$cart->qty.'-'.$get;
                    $cart->delete();
                }

                foreach ($emptyBottles as $emptyBottle) {
                    $sumTotalBottle = $emptyBottle->qty * $emptyBottle->empetybottle->price;
                    
                    // id emptybottle - qty - total
                    $emptyBottles_id[] = $emptyBottle->empetybottle->id.'-'.$emptyBottle->qty.'-'.$sumTotalBottle;
                    $emptyBottle->delete();
                }   
                // dd(collect($products_id));
    
                $price = $total;
                $data['payment_chanel'] = 'Virtual Account';
                $secret_key = 'Basic ' . config('xendit.key_auth');
                $external_id = Str::random(10);
                $data_request = Http::withHeaders([
                    'Authorization' => $secret_key
                ])->post('https://api.xendit.co/v2/invoices', [
                    'external_id' => $external_id,
                    'amount' => $price
                ]);
    
                $response = $data_request->object();
                if ($voucher != null){

                    $item = Koinpack_payment::create([
                        "external_id"    => $external_id,
                        "payment_chanel" => 'Virtual Account',
                        "users_id"          => Auth::user()->id,
                        "price"          => $price - $voucher->price ?? 0,
                        "status"          => $response->status,
                        'payment_link' => $response->invoice_url,
                        'products_id' => collect($products_id),
                        'emptyBottles_id' => collect($emptyBottles_id),
                        'receiver' => $name,
                        'phone' => $phone,
                        'address' => $address,
                        'notes' => $notes,
                        'shipping' => $shipping,
                        // 'vc' => $vc,
        
                    ]);
                }else{
                    $item = Koinpack_payment::create([
                        "external_id"    => $external_id,
                        "payment_chanel" => 'Virtual Account',
                        "users_id"          => Auth::user()->id,
                        "price"          => $price,
                        "status"          => $response->status,
                        'payment_link' => $response->invoice_url,
                        'products_id' => collect($products_id),
                        'emptyBottles_id' => collect($emptyBottles_id),
                        'receiver' => $name,
                        'phone' => $phone,
                        'address' => $address,
                        'notes' => $notes,
                        'shipping' => $shipping,
                        // 'vc' => $vc,
        
                    ]);

                }

                return redirect()->to($response->invoice_url);
            }

            

        } catch (QueryException $e) {

            if (\Request::segment(1) == 'api') {
                return response([
                    'status' => 'error',
                    'message' => 'save error ',
                    'data' => $item
                ], 401);
            }
            return back()->with('error', 'Error Create');
        }
        
    }

    public function callback()
    {
        $data = request()->all();
        $status = $data['status']; //respon xendit
        $external_id = $data['external_id'];
        Koinpack_payment::where('external_id', $external_id)->update([
            'status' => $status
        ]);
        return response()->json($data);
    }
}
