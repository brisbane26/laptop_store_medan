@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/product.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/product.js" type="module"></script>
@endpush

@push('modals-dependencies')
@include('/partials/product/product_detail_modal')
@endpush

@section('content')
<!-- product -->
<section id="product" class="pb-5">
    <div class="container">

        @if(session()->has('message'))
        {!! session("message") !!}
        @endif

        <h5 class="section-title h1">Our Product</h5>
        @can('add_product',App\Models\Product::class)
        <div class="d-flex align-items-end flex-column mb-4">
            <a style="text-decoration: none;" href="/product/add_product">
                <div class="text-right button-kemren mr-lg-5 mr-sm-3">Add Product</div>
            </a>
        </div>
        @else
        <div class="mb-5"></div>
        @endcan

        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
            <div class="d-flex justify-content-center">
                <!-- Dropdown untuk kategori -->
                <div class="input-group w-25 mx-2">
                    <select name="category" class="form-control">
                        <option value="" {{ request('category') == '' ? 'selected' : '' }}>All Categories</option>
                        <option value="new_laptop" {{ request('category') == 'new_laptop' ? 'selected' : '' }}>New Laptop</option>
                        <option value="second_laptop" {{ request('category') == 'second_laptop' ? 'selected' : '' }}>Second Laptop</option>
                        <option value="others" {{ request('category') == 'others' ? 'selected' : '' }}>Others</option>
                    </select>
                </div>
                <!-- Input pencarian -->
                <div class="input-group w-50 mx-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari Produk..." value="{{ request('search') }}">
                </div>
                <!-- Tombol search -->
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
        
        <!-- Pesan jika produk tidak ditemukan -->
        @if($product->isEmpty())
            <p class="text-center">Produk tidak ditemukan.</p>
        @else
            <div class="row justify-content-center">
                @foreach($product as $row)
                <!-- Product card -->
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="image-flip" ontouchstart="this.classList.toggle('hover');">
                        <div class="mainflip">
                            <div class="frontside">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p><img class="img-fluid" src="{{ asset('storage/' . $row->image) }}" alt="Product Image"></p>
                                        <h4 class="card-title">{{ $row->product_name }}</h4>
                                        <p class="card-text">{{ $row->category }}</p>
                                        @php
                                        $discounted_price = $row->sell_price - ($row->sell_price * $row->discount / 100);
                                        @endphp
                                        <p class="card-text">
                                            Rp.{{ number_format($discounted_price, 0, ',', '.') }} 
                                        </p>
                                        <div class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="backside">
                                <div class="card">
                                    <div class="card-body text-center mt-4">
                                        <h4 class="card-title">{{ $row->product_name }}</h4>
                                        <p class="card-text">{{ $row->orientation }}</p>

                                        <!-- detail -->
                                        <button data-id="{{ $row->product_id }}" class="btn btn-primary btn-sm detail">Detail</button>

                                        <!-- ulasan -->
                                        <a href="/review/product/{{ $row->product_id }}">
                                            <button class="btn btn-primary btn-sm ubah">Review</button>
                                        </a>

                                        <!-- [admin] ubah -->
                                        @can('edit_product',App\Models\Product::class)
                                        <a href="/product/edit_product/{{ $row->product_id }}">
                                            <button class="btn btn-primary btn-sm ubah">Edit</button>
                                        </a>
                                        @endcan
                                        @can('create_order', App\Models\Order::class)
                                        <button data-id="{{ $row->product_id }}" class="btn btn-primary btn-sm add-to-cart">
                                            Add to Cart
                                        </button>
                                        @endcan                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./product card -->
                @endforeach
            </div>
        @endif     
    </div>
</section>
<!-- product -->

@endsection
