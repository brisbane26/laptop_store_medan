@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/order.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/make_order.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
@endpush

@push('modals-dependencies')
@include('/partials/order/transfer_instructions_modal')
@endpush

@section('content')
<div class="container-fluid px-2 px-lg-4">
  <h1 class="main-title">
    {{ $title }}
  </h1>
  <div class="row">

    <!-- Left -->
    <div class="col-12 col-lg-9">
      <div class="accordion" id="accordionMain">

        <!-- top field -->

        <div class="accordion-item mb-3 px-4 py-3">
          <form action="{{ route('order.make_order') }}" method="POST">
            @csrf

            <!-- Header -->
            <div class="row fw-bold mb-3">
                <div class="col-md-4">Product Name</div>
                <div class="col-md-4">Price per pieces</div>
                <div class="col-md-3">Quantity</div>
            </div>

            <!-- List Produk -->
            @foreach($cartItems as $cart)
            <div class="row align-items-center mb-2 product-row">
                <!-- Product Name -->
                <div class="col-md-4">
                    <input type="hidden" name="product_id[]" value="{{ $cart->product->id }}">
                    <input type="text" name="product_name[]" value="{{ $cart->product->product_name }}" 
                           class="form-control" disabled>
                </div>
            
                <!-- Price -->
                <div class="col-md-4">
                  @php
                      $originalPrice = $cart->product->sell_price;
                      $discount = $cart->product->discount;
                      $finalPrice = $discount == 0 
                          ? $originalPrice 
                          : ((100 - $discount) / 100) * $originalPrice;
                  @endphp
              
                  @if ($discount == 0)
                      <input type="hidden" name="price[]" value="{{ $originalPrice }}" class="price" />
                      <span>Rp. {{ number_format($originalPrice, 0, ',', '.') }}</span>
                  @else
                      <input type="hidden" name="price[]" value="{{ $finalPrice }}" class="price" />
                      <span>
                          Rp. {{ number_format($finalPrice, 0, ',', '.') }}
                          <span class="text-decoration-line-through text-muted ms-2">
                              Rp. {{ number_format($originalPrice, 0, ',', '.') }}
                          </span>
                          <sup class="text-danger">{{ $discount }}% Off</sup>
                      </span>
                  @endif
              </div>
              
            
<!-- Quantity -->
<div class="col-md-3">
  <input type="number" name="quantity[]" 
         value="{{ old("quantity.{$loop->index}", $cart->quantity) }}" 
         class="form-control quantity" readonly>
  @error("quantity.{$loop->index}")
  <div class="text-danger" style="font-size: 12px;">
      {{ $message }}
  </div>
  @enderror
</div>
            </div>
            @endforeach
            
           @if(auth()->user()->role !== 'admin')
    <div class="row mb-3">
        <div class="col-12">Destination</div>
        <div class="form-group col-7">
            <select class="form-control @error('province') is-invalid @enderror" id="province" name="province">
                <option value="0" selected>Select Province</option>
                <!-- Dynamic provinces -->
            </select>
            @error('province') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="form-group col-5">
            <select class="form-control @error('quantity') is-invalid @enderror" id="city" name="city" disabled>
                <option value="0" selected>Select City</option>
                <!-- Dynamic cities based on province -->
            </select>
            @error('city') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="address">Address Detail</label>
        <input type="hidden" name="shipping_address" id="shipping_address">
        <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror"
               value="{{ old('address', auth()->user()->address) }}">
        @error('address') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
@else
    <!-- Admin Location Fields -->
    <input type="hidden" name="shipping_address" value="0">
    <input type="hidden" name="address" value="Store Location">
    <input type="hidden" name="payment_method" value="2">
    <option type="hidden" name="province" value="Sumatera Utara">
    <option type="hidden" name="city" value="Medan">
@endif
        </div>

        @cannot("is_admin")
        <!-- Online Banking -->
        <div class="accordion-item mb-3 ">
          <h2 class="h5 px-4 py-3 accordion-header d-flex justify-content-between align-items-center">
            <div class="form-check w-100 collapsed">
              <input class="form-check-input" type="radio" name="payment_method" id="online_bank"
                data-bs-toggle="collapse" data-bs-target="#collapseCC" aria-expanded="false" value="1" {{
                old('payment_method')=='1' ? 'checked' : '' }} onclick="hideMessage('bank')">
              <label class="form-check-label pt-1" for="online_bank" data-bs-toggle="collapse"
                data-bs-target="#collapseCC" aria-expanded="false" onclick="hideMessage('bank')">
                Transfer Bank
              </label>
              @error('payment_method')
              <div class="text-danger" id="bank_alert">{{ $message }}</div>
              @enderror
            </div>
            <span>
              <img src="{{ asset('storage/icons/online-banking.png') }}" height="50px" alt="logo online banking">
            </span>
          </h2>
          <div id="collapseCC" class="accordion-collapse collapse {{ old('payment_method')==1 ? 'show' : '' }}"
            data-bs-parent="#accordionMain">
            <div class="accordion-body">
              <div>Select Bank:</div>
              <div id="mandiri" class="form-check w-100 collapsed">
                <span><img src="{{ asset('storage/icons/bank-mandiri.svg') }}" alt="mandiri logo" width="40px"></span>
                <input type="radio" id="bank_mandiri" class="bank" name="bank_id" value="1" {{ old('bank_id')=='1'
                  ? 'checked' : '' }} style="appearance: none;">
                <label for="bank_mandiri" class="colapse_pilih_bank" data-bs-toggle="collapse"
                  data-bs-target="#section_mandiri" aria-expanded="false" style="cursor: pointer;"
                  onclick="hideBankMessage()">Bank Mandiri</label>
                <div id="section_mandiri" class="accordion-collapse collapse collapse-pilih-bank"
                  data-bs-parent="#collapseCC">
                  <!-- collapse pilih bank -->
                  <div class="divider"></div>
                  <div class="d-flex justify-content-between">
                    <small class="rek-title">No. Rekening Admin: </small>
                    <small class="rek-title">Atas Nama: </small>
                  </div>
                  <div class="d-flex justify-content-between mt-1">
                    <small class="no-rek">121 4927 1923 1412</small>
                    <small class="salin-rek">Jesica Maha</small>
                  </div>
                  <div class="divider"></div>
                  <small class="note">Akan dicek dalam 10 menit setelah pembayaran berhasil</small>
                </div>
              </div>
              <div id="bri" class="form-check w-100 collapsed">
                <span><img src="{{ asset('storage/icons/bank-bri.svg') }}" alt="bri logo" width="40px"
                    height="20px"></span>
                <input type="radio" id="bank_bri" class="bank" name="bank_id" value="2" {{ old('bank_id')=='2'
                  ? 'checked' : '' }} style="appearance: none;">
                <label for="bank_bri" class="colapse_pilih_bank" data-bs-toggle="collapse" data-bs-target="#section_bri"
                  aria-expanded="false" style="cursor: pointer;" onclick="hideBankMessage()">Bank BRI</label>
                <div id="section_bri" class="accordion-collapse collapse fade collapse-pilih-bank"
                  data-bs-parent="#collapseCC">
                  <!-- collapse pilih bank -->
                  <div class="divider"></div>
                  <div class="d-flex justify-content-between">
                    <small class="rek-title">No. Rekening Admin: </small>
                    <small class="rek-title">Atas Nama: </small>
                  </div>
                  <div class="rek-content d-flex justify-content-between mt-1">
                    <small class="no-rek">081 9082 8214 9125</small>
                    <small class="salin-rek">Jesica Maha</small>
                  </div>
                  <div class="divider"></div>
                  <small class="note">Akan dicek dalam 10 menit setelah pembayaran berhasil</small>
                </div>
              </div>
              <div id="bca" class="form-check w-100 collapsed">
                <span><img src="{{ asset('storage/icons/bank-bca.svg') }}" alt="bca logo" width="40px"></span>
                <input type="radio" id="bank_bca" class="bank" name="bank_id" value="3" {{ old('bank_id')=='3'
                  ? 'checked' : '' }} style="appearance: none;">
                <label for="bank_bca" class="colapse_pilih_bank" data-bs-toggle="collapse" data-bs-target="#section_bca"
                  aria-expanded="false" style="cursor: pointer;" onclick="hideBankMessage()">Bank BCA</label>
                <div id="section_bca" class="accordion-collapse collapse fade collapse-pilih-bank"
                  data-bs-parent="#collapseCC">
                  <!-- collapse pilih bank -->
                  <div class="divider"></div>
                  <div class="d-flex justify-content-between">
                    <small class="rek-title">No. Rekening Admin: </small>
                    <small class="rek-title">Atas Nama: </small>
                  </div>
                  <div class="rek-content d-flex justify-content-between mt-1">
                    <small class="no-rek">048 7182 4291 9123</small>
                    <small class="salin-rek">Jesica Maha</small>
                  </div>
                  <div class="divider"></div>
                  <small class="note">Akan dicek dalam 10 menit setelah pembayaran berhasil</small>
                </div>
              </div>
              <div id="bni" class="form-check w-100 collapsed">
                <span><img src="{{ asset('storage/icons/bank-bni.svg') }}" alt="bni logo" width="40px"></span>
                <input type="radio" id="bank_bni" class="bank" name="bank_id" value="4" {{ old('bank_id')=='4'
                  ? 'checked' : '' }} style="appearance: none;">
                <label for="bank_bni" class="colapse_pilih_bank" data-bs-toggle="collapse" data-bs-target="#section_bni"
                  aria-expanded="false" style="cursor: pointer;" onclick="hideBankMessage()">Bank BNI</label>
                <div id="section_bni" class="accordion-collapse collapse fade collapse-pilih-bank"
                  data-bs-parent="#collapseCC">
                  <!-- collapse pilih bank -->
                  <div class="divider"></div>
                  <div class="d-flex justify-content-between">
                    <small class="rek-title">No. Rekening Admin: </small>
                    <small class="rek-title">Atas Nama: </small>
                  </div>
                  <div class="rek-content d-flex justify-content-between mt-1">
                    <small class="no-rek">988 2187 9823 2332</small>
                    <small class="salin-rek">Jesica Maha</small>
                  </div>
                  <div class="divider"></div>
                  <small class="note">Akan dicek dalam 10 menit setelah pembayaran berhasil</small>
                </div>
              </div>
              @error('bank_id')
              <div class="text-danger mt-3" id="bank_id_alert">{{ $message }}</div>
              @enderror

              <!-- petunjuk -->
              <div class="container mt-3" id="container-petunjuk">
                <div class="mt-4">
                  <h6>Transfer Instructions</h6>
                  <p class="text-muted">BRI Bank Virtual Accounts only accept transfers from BRI Bank accounts. For
                    payments with a BRI
                    Syariah Bank account, use a Bank Mandiri virtual account. The following are transfer instructions if
                    you use Bank BRI.</p>
                </div>
                <div class="accordion" id="accordionPetunjuk">
                  <div class="item">
                    <div class="item-header" id="headingOne">
                      <h2 class="mb-0">
                        <button class="btn btn-link collapsed d-flex justify-content-between align-items-center"
                          type="button" data-bs-toggle="collapse" data-bs-target="#atm" aria-expanded="false"
                          aria-controls="atm">
                          <div class="title-accordion-petunjuk">ATM</div>
                          <img class="title-accordion-petunjuk" src="{{ asset('storage/icons/angle-down.svg') }}"
                            alt="angle down fas icon" width="18px">
                        </button>
                      </h2>
                    </div>
                    <div id="atm" class="collapse" aria-labelledby="headingOne" data-bs-parent="#accordionPetunjuk">
                      <div class="t-p">
                        <ol>
                          <li>Select Other Transactions > Payments > Others > select BRIVA.</li>
                          <li>Enter no. BRIVA listed on the Payment page (consisting of 3 (three) Bank code numbers +
                            User mobile number/random number) and select True.</li>
                          <li>Double-check the information on the screen. Make sure the Merchant is Shopee, the total
                            bill is correct, and your username is {username}. If it is correct, select Yes.</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                  <div class="item">
                    <div class="item-header" id="headingTwo">
                      <h2 class="mb-0">
                        <button class="btn btn-link collapsed d-flex justify-content-between align-items-center"
                          type="button" data-bs-toggle="collapse" data-bs-target="#e-banking" aria-expanded="false"
                          aria-controls="e-banking">
                          <div class="title-accordion-petunjuk">E-Banking</div>
                          <img class="title-accordion-petunjuk" src="{{ asset('storage/icons/angle-down.svg') }}"
                            alt="angle down fas icon" width="18px">
                        </button>
                      </h2>
                    </div>
                    <div id="e-banking" class="collapse" aria-labelledby="headingTwo"
                      data-bs-parent="#accordionPetunjuk">
                      <div class="t-p">
                        <ol>
                          <li>Select the Payment menu > select BRIVA.</li>
                          <li>Select the original account, then select Fill in Pay Code and enter the Pay Code listed on
                            the Payment page (consisting of 3 (three) Bank code numbers + User mobile number/random
                            number) and select Send.</li>
                          <li>Check the information on the screen. Make sure the Merchant is Shopee, the total bill is
                            correct, and your username is {username}. If it is correct, enter your E-Banking Password
                            and mToken, then select Send.</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                  <div class="item">
                    <div class="item-header" id="headingThree">
                      <h2 class="mb-0">
                        <button class="btn btn-link collapsed d-flex justify-content-between align-items-center"
                          type="button" data-bs-toggle="collapse" data-bs-target="#m-banking" aria-expanded="false"
                          aria-controls="m-banking">
                          <div class="title-accordion-petunjuk">M-Banking</div>
                          <img class="title-accordion-petunjuk" src="{{ asset('storage/icons/angle-down.svg') }}"
                            alt="angle down fas icon" width="18px">
                        </button>
                      </h2>
                    </div>
                    <div id="m-banking" class="collapse" aria-labelledby="headingThree"
                      data-bs-parent="#accordionPetunjuk">
                      <div class="t-p">
                        <ol>
                          <li>Go to BRI Mobile Banking main page > Payment > select BRIVA.</li>
                          <li>Enter no. BRIVA listed on the Payment page (consisting of 3 (three) Bank code numbers +
                            User mobile number/random number).</li>
                          <li>Enter your PIN > select Send. If a confirmation message appears for transactions using
                            SMS, select OK. Transaction status will be sent via SMS and can be used as proof of payment.
                          </li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <a class="btn btn-info px-1 py-0" href="#TransferInstructionsModal" data-bs-toggle="modal">See
                    More</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endcannot
        {{-- COD --}}
        <div class="accordion-item mb-3 border">
          <h2 class="h5 px-4 py-3 accordion-header d-flex justify-content-between align-items-center">
            <div class="form-check w-100 collapsed">
              <input class="form-check-input" type="radio" name="payment_method" id="cod" data-bs-toggle="collapse"
                data-bs-target="#collapsePP" aria-expanded="false" value="2" {{ old('payment_method')=='2' ? 'checked'
                : '' }} onclick="hideMessage('cod')">
              <label class="form-check-label pt-1" for="cod" data-bs-toggle="collapse" data-bs-target="#collapsePP"
                aria-expanded="false" onclick="hideMessage('cod')">
                Cash on Delivery
              </label>
              @error('payment_method')
              <div class="text-danger" id="cod_alert">{{ $message }}</div>
              @enderror
            </div>
            <span>
              <img src="{{ asset('storage/icons/cash-on-delivery.png') }}" height="50px" alt="logo COD" />
            </span>
          </h2>
          <div id="collapsePP" class="accordion-collapse collapse  {{ old('payment_method')==2 ? 'show' : '' }}"
            data-bs-parent="#accordionMain">
            <div class="accordion-body">
              <div class="content-cod">
                <div class="note-cod">Note: use this method if you want to do COD transactions</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Right -->
    <div class="col-12 col-lg-3">
      <div class="card position-sticky top-0">
        <div class="p-3 bg-light bg-opacity-10">
          <h6 class="card-title mb-3">Order Summary</h6>
          {{-- loading --}}
          <div id="loading_transaction" style="display: none">
            <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_raiw2hpe.json" background="transparent"
              speed="1" style="width: auto; height: 125px;" loop autoplay>
            </lottie-player>
          </div>
          {{-- transaction resume --}}
          <div id="transaction">
            <div class="d-flex justify-content-between mb-1 small">
              <span>Subtotal</span> <span><span>Rp. </span> <span id="sub-total">0</span></span>
            </div>

            @if(auth()->user()->role !== 'admin')
            <div class="d-flex justify-content-between mb-1 small">
              <span>Delivery</span>
              <span><span>Rp. </span><span id="shipping" data-shippingCost="0">0</span></span>
            </div>

            <input type="hidden" name="coupon_used" id="coupon_used" value="0">
            <!-- Coupon section for regular customers -->
            <div class="d-flex justify-content-between mb-1 small">
              <span>Coupon 
                @if (auth()->user()->coupon == 0)
                (no coupon)
                @else
                <span class="align-items-center">
                  <label for="use_coupon" style="cursor:pointer">(use coupon</label>
                </span>
                <span>
                  <input id="use_coupon" type="checkbox" onchange="changeStatesCoupon()">
                </span>
                )
                @endif
              </span>
              <span id="coupon" data-valueCoupon="{{ auth()->user()->coupon }}">
                {{ auth()->user()->coupon }} Coupon
              </span>
            </div>
            @else
            <!-- Admin/Cashier version - no shipping or coupons -->
            <input type="hidden" name="shipping_cost" value="0">
            <input type="hidden" name="coupon_used" value="0">
            @endif
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-4 small">
            <span>TOTAL</span> <strong class="text-dark"><span>Rp. </span><span id="total">0</span></strong>
            <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price', '0') }}">
          </div>
          <div class="form-group small mb-3">
            Make sure you really understand the order you make. If you want to get more information please contact <a
              class="link-danger"
              href="https://wa.me/6281230451084?text=Saya%20ingin%20menanyakan%20detail%20terkait%20produk%20anda"
              target="_blank" style="text-decoration: none;">@admin</a>
          </div>
          <button type="submit" class="btn btn-primary w-100 mt-2">Submit</button>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection