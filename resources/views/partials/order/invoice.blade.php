<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
        }
        .invoice-details, .invoice-footer {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-details th, .invoice-details td, .invoice-footer th, .invoice-footer td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-details th {
            background-color: #f2f2f2;
        }
        .invoice-footer {
            margin-top: 30px;
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .invoice-total {
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: right;
        }
        .total-cell {
            font-weight: bold;
        }
        .green-cell {
            background-color: #4CAF50;
            color: white;
        }
        .black-text {
            color: black; /* Warna hitam untuk teks Total */
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice</h1>
        <p>Invoice ID: #{{ $order->id }}</p>
        <p>Date: {{ $order->updated_at->format('d M Y') }}</p> <!-- Menggunakan tanggal update untuk invoice -->
    </div>

    <table class="invoice-details">
        <tr>
            <th>Customer</th>
            <td>{{ $order->user->fullname }} ({{ $order->user->email }})</td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td>{{ $order->payment->payment_method }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $order->status->order_status }}</td>
        </tr>
        <tr>
            <th>Shipping Address</th>
            <td>{{ $order->shipping_address }}</td>
        </tr>
    </table>

    <table class="invoice-details">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price (Rp)</th>
                <th>Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPrice = 0; @endphp
            @foreach ($order->orderDetails as $detail)
                @php $totalPrice += $detail->price * $detail->quantity; @endphp
                <tr>
                    <td>{{ $detail->product->product_name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Menghitung Ongkir dari Selisih Total Price dan Subtotal -->
    @php
        $subtotal = $totalPrice;
        $shippingCost = $order->total_price - $subtotal;
        $total = $subtotal + $shippingCost;
    @endphp

    <!-- Menampilkan Ongkir dan Total, dengan jumlah kolom yang sama dengan produk -->
    <table class="invoice-details">
        <tr>
            <th></th> <!-- Kolom kosong agar sejajar -->
            <th class="total-cell">Shipping Cost (Rp)</th>
            <td>{{ number_format($shippingCost, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th></th> <!-- Kolom kosong agar sejajar -->
            <th class="black-text">Total (Rp)</th> <!-- Warna teks Total jadi hitam -->
            <td>{{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="invoice-footer">
        <p>Thank you for your order!</p>
    </div>
</body>
</html>
