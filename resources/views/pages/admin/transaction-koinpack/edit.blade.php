@extends('layouts.admin')
@section('title','Transactions')
@section('content')
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Transactions</h1>
      <div class="section-header-breadcrumb">
        
        <div class="breadcrumb-item"><a>Transactions</a></div>
        <div class="breadcrumb-item active">Edit Transactions</div>
      </div>
    </div>

    <div class="section-body">
      
      <div class="card">
        <div class="card-header">
          <h4>Edit Transaction</h4>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>                            
        @endif
       
          
            <div class="card-body">
                <form action="{{ route('transactions.update', $item->id) }}" method="POST">{{-- untuk menyimpan sebuah data menggunakan functioan 'store' --}}
                  @csrf{{-- setiap buat form pakai @csrf --}}
                  @method('PUT')
                  
                  <div class="form-group">
                    <label for="users_id">User</label>
                    <select name="users_id" class="form-control" required>
                      @if ($item->users_id)
                        <option value="{{$item->users_id}}">.: {{$item->customer_full->name}} :.</option>
                      @else
                        <option value="">.: User Removed :.</option>
                      @endif
                      
                      @forelse ($users as $user)
                          <option value="{{$user->id}}">{{$user->name}}</option>
                      @empty
                          <option value="">.: Null :.</option>
                      @endforelse
                    </select>
                  </div>

                  {{-- <div class="form-group">
                    <label for="products_id">Product</label> --}}
                    {{-- {{var_dump($item->products_id)}} --}}

                    {{-- <select name="products_id" class="form-control" required>
                      @if ($item->products_id)
                        <option value="{{$item->products_id}}">.: {{$item->product->name ?? ''}} :.</option>
                      @else
                        <option value="">.: Product Removed :.</option>
                      @endif
                      
                      @forelse ($products as $product)
                          <option value="{{$product->id}}">{{$product->name}}</option>
                      @empty
                          <option value="">.: Null :.</option>
                      @endforelse
                    </select>
                  </div> --}}


                  {{-- <div class="form-group">
                    <label for="qty">Qty</label>
                    <input type="number" class="form-control" name="qty" placeholder="Qty" value="{{ $item->qty }}" required>
                  </div> --}}
                  <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" name="status" placeholder="Status" value="{{ $item->status }}" required>
                  </div>

                  <div class="form-group">
                    <label for="totalprice">Total Price</label>
                    <input type="number" class="form-control" name="price" placeholder="Total Price" value="{{ $item->price }}" required>
                  </div>

                  <div class="form-group">
                    <label for="cashback">Cashback</label>
                    <input type="number" class="form-control" name="cashback_payment" placeholder="CashBack" value="{{ $item->customer_full->cashback }}" required>
                  </div>

                  <div class="form-group">
                    <label for="voucher">Voucher</label>
                    <input type="text" class="form-control" name="voucher" placeholder="Voucher" value="{{$item->voucher}}" required>
                  </div>

                  <div class="form-group">
                    <label for="voucher">Notes</label>
                    <input type="text" class="form-control" name="notes" placeholder="Notes" value="{{ $item->notes }}" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="shipping">Shipping</label>
                    <input type="text" class="form-control" name="shipping" placeholder="shipping" value="{{ $item->shipping }}" required>
                  </div>
                  <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa-solid fa-pen-to-square text-white-50"></i> Update
                  </button>
                </form>
            </div>
        
      
        <div class="card-footer bg-whitesmoke">
          
        </div>
      </div>
    </div>
  </section>
  </div>
@endsection

@push('addon-script')
  
@endpush