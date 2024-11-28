<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {

            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }


            .invoice-container {
        max-width: 800px;
        margin: 30px auto;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .invoice-header .invoice-id-date {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .invoice-header h1 {
        font-size: 30px;
        color: #000000;
        margin: 0;
    }

    .invoice-header .invoice-id-date,
    .invoice-header .address {
        font-size: 14px;
        color: #777;
    }

    .address {
        text-align: right;
        max-width: 50%; 
        word-wrap: break-word; 
    }

    .details-container {
        margin-top: 20px;
    }

    .details-container table {
        width: 100%;
        margin-bottom: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .invoice-items {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .invoice-items th, .invoice-items td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    .invoice-items th {
        background-color: #f9f9f9;
    }

    .total-row {
        font-weight: bold;
        background-color: #f9f9f9;
    }

    .footer {
        text-align: center;
        margin-top: 30px;
        color: #777;
        font-size: 14px;
    }

    .footer p {
        margin: 5px;
    }

    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div class="invoice-header">
            <div>
                <h1>Laptop Store <br> Medan</h1>
            </div>
            <div class="invoice-id-date-address">
                <div class="address">
                    <p>Invoice ID: #{{ $order->id }}</p>
                    <p>Date: {{ $order->updated_at->format('d M Y') }}</p>
                    <p>Address: Komp.Garuda Mas no.4,<br>
                    Jl.Berlian I, Deli Tua Barat</p>
                    <p>Zip Code: 20361</p>
                </div>
            </div>
        </div>

        <!-- Order and User Details -->
        <table class="details-container">
            <tr>
                <th colspan="2">Order Details</th>
                <th colspan="2">User Details</th>
            </tr>
            <tr>
                <th>Order ID</th>
                <td>{{ $order->id }}</td>
                <th>Customer Name</th>
                <td>{{ $order->user->fullname }}</td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>{{ $order->payment->payment_method }}</td>
                <th>Email</th>
                <td>{{ $order->user->email }}</td>
            </tr>
            <tr>
                <th>Ordered at</th>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <th>Phone</th>
                <td>{{ $order->user->phone }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $order->status->order_status }}</td>
                <th>Shipping Address</th>
                <td>{{ $order->shipping_address }}</td>
            </tr>
        </table>

        <div>
            <h1>Order Items</h1>
        <table class="invoice-items">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Price (Rp)</th>
                    <th>Quantity</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPrice = 0; @endphp
                @foreach ($order->orderDetails as $detail)
                    @php 
                        $subtotal = $detail->price * $detail->quantity;
                        $totalPrice += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $detail->product->id }}</td>
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        @php
            // Menghitung subtotal dan biaya pengiriman
            $subtotal = $totalPrice;
            $shippingCost = $order->total_price - $subtotal; // Menghitung biaya pengiriman
            $total = $subtotal + $shippingCost; // Total dengan biaya pengiriman
        @endphp

        <!-- Total Section -->
        <table class="details-container">
            <tr>
                <th>Shipping Cost (Rp)</th>
                <td>{{ number_format($order->total_price - $totalPrice, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <th>Total (Rp)</th>
                <td>{{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for shopping with us :)</p>
        </div>
    </div>
</body>
</html>