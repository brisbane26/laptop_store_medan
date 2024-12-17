@push('css-dependencies')
    <link rel="stylesheet" type="text/css" href="/css/product.css" />
@endpush

@push('scripts-dependencies')
    <script src="/js/product.js" type="module"></script>
@endpush

@push('modals-dependencies')
@include('/partials/product/product_detail_modal')
@endpush

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Store Medan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/landing.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      @stack('css-dependencies')
</head>
<body>
    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <img src="{{ asset('storage/logo.png') }}" alt="Laptop Store Logo" width="60">
            <a class="navbar-brand fw-bold" href="/">Laptop Store Medan</a>

            <!-- Login/Register Buttons -->
            <div class="ms-auto">
                <a href="/auth/login" class="btn btn-outline-primary me-2">Login</a>
                <a href="/auth/register" class="btn btn-primary">Register</a>
            </div>
        </div>
    </nav>

    <!-- Jumbotron Section -->
    <div class="jumbotron">
        <div class="jumbotron-content">
            <h1>Welcome to Laptop Store Medan</h1>
            <p>Your one-stop shop for the best laptops!</p>
            <!-- Tombol Get Started -->
            <a href="#products-section" class="get-started-btn">Get Started</a>
        </div>
    </div>

    <!-- Product Section -->
    <div class="container my-5" id="products-section">
        <form method="GET" action="{{ route('landing.index') }}" class="mb-4">
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
            <div class="product-card-container">
                @foreach($product as $row)
                <!-- Product card -->
                <div class="col">
                    <div class="product-card">
                        <div class="card">
                            <div class="card-body text-center">
                                <p><img class="img-fluid" src="{{ asset('storage/' . $row->image) }}" alt="Product Image"></p>
                                <h4 class="card-title">{{ $row->product_name }}</h4>
                                <p class="product-category">{{ $row->category }}</p>
                                @php
                                $discounted_price = $row->sell_price - ($row->sell_price * $row->discount / 100);
                                @endphp
                                <p class="price">Rp.{{ number_format($discounted_price, 0, ',', '.') }}</p>
                                <!-- Tombol Pesan yang mengarah ke login -->
                                <a href="/auth/login" class="product-button"><i class="fa fa-plus"></i> Pesan</a>
                                <button data-id="{{ $row->product_id }}" class="detail-button">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    @stack('modals-dependencies')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="/js/datatables-simple.js"></script>
  
    <script src="/js/scripts.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
    @stack('scripts-dependencies')

</body>
</html>
