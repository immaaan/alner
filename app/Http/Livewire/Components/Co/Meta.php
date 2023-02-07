<?php

namespace App\Http\Livewire\Components\Co;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Koinpack_customer;
use App\Koinpack_shopping_cart;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Meta extends Component
{
    public $user;
    public $customer;

    public $name;
    public $phone;
    public $address;
    public $vc;
    public $notes;
    public $total;
    public $totalProduct;
    public $totaleEmptyBottle;
    public $cashback = 0;
    public $shipping = 10;
    public $cashbackUser = 0;

    protected $listeners = [
        'updatedQty' => 'setTotal',
        'reloadCo' => '$refresh',
        'store' => 'store',
    ];

    public function setTotal()
    {
        
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

        $totalProduct = 0;
        $totaleEmptyBottle = 0;


        foreach ($carts as $cart) {
            $get = $cart->product->price * $cart->qty;
            $totalProduct += $get;
        }

        foreach ($emptyBottles as $emptyBottle) {
            $sumQtyBottle = $emptyBottle->qty * $emptyBottle->empetybottle->price;

            $totaleEmptyBottle +=$sumQtyBottle;
        }

        $this->totaleEmptyBottle = $totaleEmptyBottle;
        $this->totalProduct = $totalProduct;

        // $this->$cashbackUser = User::where('id', Auth::user()->id)->first()->cashback;

    }

    public function mount($user, $customer)
    {
        $this->setTotal();
        $this->cashbackUser = $user->cashback;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->address = $customer->address;
    }

    public function render()
    {
        return view('livewire.components.co.meta');
    }

    public function store($total)
    {
       dd($total);
        return App::call('App\Http\Controllers\CheckoutController@store' , [
            'name' => $this->name,
            'cashback' => $cashback,
            'phone' => $this->phone,
            'address' => $this->address,
            'notes' => $this->notes,
            'shipping' => $this->shipping,
            'total' => $total,
            'vc' => $this->vc,
        ]);

    }
}
