<?php

namespace App\Http\Livewire\Components\Co;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Koinpack_customer;
use App\Koinpack_shopping_cart;
use App\Koinpack_voucher;
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
    public $nominalVoucher;
    public $totalProduct;
    public $totaleEmptyBottle;
    public $cashback = 0;
    public $shipping = 0;
    public $cashbackUser = 0;
    public $firstCashBack = 0;
    public $isChecked = false;

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

    public function getNominalVoucher() {
        $title = $this->vc;
        $voucher = Koinpack_voucher::where('title', $title)->first();
        
        if ($voucher == null){
            $this->nominalVoucher = 0;
            return response()->json([
                'req' => $this->vc,
                'msg' => 'Voucher Tidak Ditemukan!'
            ]);
        }else{
            $this->nominalVoucher = $voucher->price;
            return response()->json([
                'req' => $this->vc,
                'msg' => 'Voucher Ditemukan',
                'price' => $voucher->price
            ]);
        }   
    }

    public function setValue($total, $cashbackUser) {
        $this->isChecked = !$this->isChecked;
        if ($this->isChecked) {
            if ($total > $cashbackUser) {
                $this->total = $this->cashbackUser;
                $this->cashbackUser = 0;
            }
            else {
                $this->cashbackUser = $this->firstCashBack - $total;
                $this->total = $total;
            }
        }
        else {
            $this->total = 0;
            $this->cashbackUser = $this->firstCashBack;
        }
    }

    public function mount($user, $customer)
    {
        $this->setTotal();
        $this->cashbackUser = $user->cashback;
        $this->firstCashBack = $user->cashback;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->address = $customer->address;
    }

    public function render()
    {
        return view('livewire.components.co.meta');
    }

    public function store($total, $cashback, $cashbackUser)
    {
        return App::call('App\Http\Controllers\CheckoutController@store' , [
            'name' => $this->name,
            'cashback' => $cashback,
            'cashbackUser' => $cashbackUser,
            'phone' => $this->phone,
            'address' => $this->address,
            'notes' => $this->notes,
            'shipping' => $this->shipping,
            'total' => $total,
            'vc' => $this->vc,
        ]);

    }
}
