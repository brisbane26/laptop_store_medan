@extends('/layouts/main')

@section('title', $title)

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/order.css" />
@endpush

@section('content')
<div class="container">
    <h1>Cart Page</h1>

    {{-- Tampilkan pesan jika ada notifikasi --}}
    @if(session('message'))
        <div class="alert alert-warning">
            {{ session('message') }}
        </div>
    @endif

    {{-- Jika keranjang kosong --}}
    @if($cartItems->isEmpty())
        <p>Keranjang Anda kosong. <a href="{{ route('products.index') }}">Belanja sekarang</a></p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Kuantitas</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartItems as $item)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="50" height="50" style="margin-right: 10px;">
                            <span>{{ $item->product->product_name }}</span>
                        </div>
                    </td>
                    <td>
                        @php
                            $originalPrice = $item->product->price;
                            $discount = $item->product->discount;
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
                    </td>
                    <td>
                        <form method="POST" action="{{ route('cart.remove') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <button type="submit" class="btn btn-sm btn-warning">-</button>
                        </form>
                        {{ $item->quantity }}
                        <button type="button" class="btn btn-sm btn-success" data-product-id="{{ $item->product_id }}">+</button>
                    </td>
                    <td>
                        @php
                            $total = $finalPrice * $item->quantity;
                        @endphp
                        Rp. {{ number_format($total, 0, ',', '.') }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('cart.delete') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <a href="{{ route('cart.checkout') }}" class="btn btn-primary">Checkout</a>
    @endif
</div>

<script>
    document.querySelectorAll('.btn-success').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.productId;
    
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 }),
            })
            .then(response => response.json())
            .then(data => {
                // Hapus notifikasi dan reload halaman
                location.reload(); // Reload halaman untuk memperbarui keranjang
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
    
@endsection
