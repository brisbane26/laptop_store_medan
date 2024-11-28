<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }
        h1,h2,h3,h4,h5,h6,p,span,label {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }
        table thead th {
            height: 28px;
            text-align: left;
            font-size: 16px;
            font-family: sans-serif;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }
        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }
        .total-heading {
    font-size: 20px;
    background-color: #f2f2f2;
    padding: 10px;
    border-top: 2px solid #414ab1;
}

        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }
        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: left;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 400;
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

    .white-bg {
    background-color: #ffffff !important; /* Latar belakang putih */
}

.text-end-company-data {
    text-align: right;/* Meratakan teks kanan */
}


    .no-border {
            border: 1px solid #fff !important;
        }
    .bg-blue {
    background-color: #0056b3; /* Biru lebih gelap */
    color: white;
}
.bg-blue th{
    color: white
}
.total-row {
    background-color: #f0f0f0;
    font-weight: bold;
}


    </style>
</head>
<body>

    <table class="order-details">
        <thead>
            <tr class="white-bg">
                <th width="50%" colspan="2">
                    <h2 class="text-start">Laptop Store Medan</h2>
                </th>
                <th width="50%" colspan="2" class="text-end text-end-company-data">
                    <span>Invoice ID: #{{ $order->id }}</span> <br>
                    <span>Date: {{ $order->updated_at->format('d M Y') }}</span> <br>
                    <span>Zip code : 20361</span> <br>
                    <span>Address: Komp.Garuda Mas no.4,<br>
                        Jl.Berlian I, Deli Tua Barat</span> <br>
                </th>
            </tr>
            
            <tr class="bg-blue">
                <th width="50%" colspan="2">Order Details</th>
                <th width="50%" colspan="2">User Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Order Id</td>
                <td>{{ $order->id }}</td>

                <td>Customer Name</td>
                <td>{{ $order->user->fullname }}</td>
            </tr>
            <tr>
                <td>Payment Mode</td>
                <td>{{ $order->payment->payment_method }}</td>

                <td>Email</td>
                <td>{{ $order->user->email }}</td>
            </tr>
            <tr>
                <td>Ordered Date</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>

                <td>Phone</td>
                <td>{{ $order->user->phone }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>{{ $order->status->order_status }}</td>

                <td>Address</td>
                <td>{{ $order->shipping_address }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    Order Items
                </th>
            </tr>
            <tr class="bg-blue">
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
                <td width="10%">{{ $detail->product->id }}</td>
                <td>{{ $detail->product->product_name }} </td>
                <td width="10%">{{ number_format($detail->price, 0, ',', '.') }}</td>
                <td width="10%">{{ $detail->quantity }}</td>
                <td width="15%" class="fw-bold">{{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

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
    <tr>
        <th>Total (Rp)</th>
        <td class="total-heading">{{ number_format($order->total_price, 0, ',', '.') }}</td>
    </tr>
    
</table>


    <br>
    <p class="text-center">
        Thank your for shopping with us :)
    </p>

</body>
</html>