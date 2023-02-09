<div class="row border mt-100">
    <div class="col-lg-8 px-0">
      <div class="mb-4 py-4 px-4">
        <h2>Customer Details</h2>
        <div class="flex flex-column mt-4">
          <div class="flex mb-2">Email</div>
          <div class="flex">
            <input type="text" disabled value="{{ $user->email }}" placeholder="Email" class="form-control text-brand py-2 font-16">
          </div>
        </div>
        <div class="flex flex-column mt-3">
          <div class="flex mb-2">Full Name</div>
          <div class="flex">
            <input type="text" wire:model="name" value="{{ $name }}" placeholder="Name" class="form-control py-2 font-16">
          </div>
        </div>
        <div class="flex flex-column mt-3">
          <div class="flex mb-2">Phone</div>
          <div class="flex">
            <input type="text" wire:model="phone" value="{{ $phone }}" placeholder="Phone" class="form-control text-brand py-2 font-16">
          </div>
        </div>
        <div class="flex flex-column mt-3">
          <div class="flex mb-2">Address</div>
          <div class="flex">
            <input type="text" wire:model="address" value="{{ $address }}" placeholder="Address" class="form-control text-brand py-2 font-16">
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 px-0 bg-success">
      <div class="py-4 px-4 height-100" style="background-color: #F1F5F9">
        <div class="py-2 d-flex px-0 align-items-center justify-content-between">
          <input wire:model="vc" type="text" value="{{ $vc }}" placeholder="Enter a voucher" id="voucher" class="form-control text-brand py-2">
          <a class="btn btn-primary cek-vc" wire:click='getNominalVoucher({{$total}})' style="height : 35px;">Cek</a>
        </div>
        <div class="py-2 d-flex px-0 align-items-center justify-content-between">
          <div id="msg-vc">
            @if($nominalVoucher > 0) 
              Voucher Ditemukan
            @endif
          </div>
        </div>
        <div class="py-2 d-flex px-0 align-items-center justify-content-between">
          <textarea wire:model="notes" type="text" placeholder="Notes" class="form-control text-brand py-2">{{ $notes }}</textarea>
        </div>
        {{-- <div class="py-2 d-flex align-items-center justify-content-between">
          <span>Subtotal</span>
          <span>3000</span>
        </div> --}}
        <div class="py-2 d-flex flex-row align-items-center justify-content-between">
          <select name="shipping" wire:model="shipping" class="form-select" required>
            <option value="0" >Diambil Sendiri (di Kantor Alner)</option>
            <option value="0">Dijemput Alner (kemasan kosong)</option>
            <option 
                @if ($total < 150000)
                  value="20000"
                @elseif ($total < 200000)
                  value="10000"
                @else
                  value="0"
                @endif
            >Dikirim Alner</option>
          </select>
        </div>
        
        <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between div-nominal hilang">
          <span class="fs-6">Voucher Nominal</span>
          <span class="fs-6 vc-nominal" >{{rupiah($nominalVoucher)}}</span>
        </div>
        
        <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
          <span class="fs-6">Shipping</span>
          <span id="shippingPrice" class="fs-6">{{ rupiah($shipping) }}</span>
        </div>
        @php
            $totaleEmptyBottlePlusShipping = $totaleEmptyBottle+$shipping;
            $totalEmptyBottle_TotalProduct = $totaleEmptyBottle-$totalProduct;

            $totalTotalProduct_EmptyBottle = $totalProduct-$totaleEmptyBottle;

        @endphp
        
        @if ($totalProduct && $totaleEmptyBottle || $totalProduct && !$totaleEmptyBottle)
          @if ($totalTotalProduct_EmptyBottle > 0)
            @if($totalTotalProduct_EmptyBottle < $shipping)
            <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
              <span class="fs-6">Total</span>
              <span id="totalPrice" class="fs-6">{{ rupiah($total = ($totalTotalProduct_EmptyBottle+$shipping) - $total - $nominalVoucher) }}</span>
            </div>
            @elseif($totalTotalProduct_EmptyBottle > $shipping)
              <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
                <span class="fs-6">Total</span>
                <span id="totalPrice" class="fs-6">{{ rupiah($total = ($totalTotalProduct_EmptyBottle-$shipping) - $total - $nominalVoucher) }}</span>
              </div>
            @endif
          @else
            @if($totalEmptyBottle_TotalProduct < $shipping)
              <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
                <span class="fs-6">Total</span>
                <span class="fs-6">{{ rupiah($total = $shipping - $totalEmptyBottle_TotalProduct) }}</span>
              </div>  
            
            @else
              <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
                <span class="fs-6">Total</span>
                <span class="fs-6">{{ rupiah($total = 0) }}</span>
              </div>
              <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
                <span class="fs-6 text-success">Sisa <span class="badge bg-success">{{ rupiah($cashback = $totalEmptyBottle_TotalProduct - $shipping) }}</span> akan dimasukkan ke cashback akun kamu</span>
              </div>
              
            @endif
          @endif
        @elseif (!$totalProduct && $totaleEmptyBottle)
          @if($totaleEmptyBottle < $shipping)
            <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
              <span class="fs-6">Total</span>
              <span class="fs-6">{{ rupiah($total = $shipping - $totaleEmptyBottle) }}</span>
            </div>  
          
          @elseif($totaleEmptyBottle > $shipping)
            <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
              <span class="fs-6">Total</span>
              <span class="fs-6">{{ rupiah($total = 0) }}</span>
            </div>
            <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
              <span class="fs-6 text-success">Sisa <span class="badge bg-success">{{ rupiah($cashback = $totaleEmptyBottle - $shipping) }}</span> akan dimasukkan ke cashback akun kamu</span>
            </div>
          @endif
          
        @endif
        <!-- Default switch -->
        <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
          <div class="custom-control custom-switch">
            <input wire:click='setValue({{$total}},{{$cashbackUser}})' type="checkbox" class="custom-control-input" id="customSwitches" @if ($total <= 0 && !$isChecked) disabled @endif @if($isChecked) checked @endif>
            <label class="custom-control-label" for="customSwitches">Gunakan Cashback</label>

          </div>
          @if ($total <= $cashbackUser)
            <span class="fs-6">- {{  rupiah($total) }}</span>
          @else
            <span class="fs-6">- {{  rupiah($firstCashBack) }}</span>
          @endif
        </div>
        

        <div id="useCashBack" >
          @if ($isChecked)
            @if ($total <= $firstCashBack)
              <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
                  <span class="fs-6 text-danger">Sisa cashback <span class="badge bg-danger">{{rupiah($cashbackUser)}}</span> setelah transaksi</span>
              </div>
            @else
              <div class="border-top mt-2 py-3 pt-4 d-flex align-items-center justify-content-between">
                <span class="fs-6 text-danger">Sisa cashback <span class="badge bg-danger">Rp. 0</span> setelah transaksi</span>
              </div>
            @endif
          @endif
        </div>


        <div class="mt-4">
          {{-- {{var_dump($total)}} --}}
        <button wire:click="store({{$total}},{{$cashback}},{{$cashbackUser}})" class="btn btn-success width-100  @if (!$totalProduct && !$totaleEmptyBottle) disabled @endif">Checkout</button>
          {{-- <a href="{{ route('checkout') . '?name=' . urlencode($name) . '&cashback=' . urlencode($cashback) . '&phone=' . urlencode($phone) . '&address=' . urlencode($address) . '&notes=' . urlencode($notes) . '&shipping=' . urlencode($shipping) . '&total=' . urlencode($total) . '&vc=' . $vc }}" class="btn btn-success width-100  @if (!$totalProduct && !$totaleEmptyBottle) disabled @endif">Checkout</a> --}}
        </div>
      </div>
    </div>
    
  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


  <script>
  
  // function cek(){
  //   $.ajax({
  //           url: "co",
  //           type: "get",
  //           data: {
  //               "_token": "{{ csrf_token() }}",
  //               search: $('#voucher').val()
  //           },
  //           success: function (data) {
  //               console.log(data.price);
  //             $('#msg-vc').html(data.msg);
  
  //             var bilangan = data.price;
  //             nominalVoucher = bilangan
  //             console.log(data);
     
  //             if (data.msg == 'Voucher Ditemukan') {
  //               $('.vc-nominal').html('Rp. '+data.price);
  //             }else{
  //               $('.vc-nominal').html('Rp. 0');
  
  //             }
       
  //           }
  //       })
  // }
    
  </script>

  