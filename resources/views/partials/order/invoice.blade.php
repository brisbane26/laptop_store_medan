<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
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
        .invoice-footer {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice</h1>
        <p>Invoice ID: #{{ $order->id }}</p>
        <p>Date: {{ $order->created_at->format('d M Y') }}</p>
    </div>

    <table class="invoice-details">
        <tr>
            <th>Customer</th>
            <td>{{ $order->user->fullname }} ({{ $order->user->email }})</td>
        </tr>
        <tr>
            <th>Product</th>
            <td>{{ $order->product->product_name }}</td>
        </tr>
        <tr>
            <th>Quantity</th>
            <td>{{ $order->quantity }}</td>
        </tr>
        <tr>
            <th>Total Price</th>
            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
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

    <div class="invoice-footer">
        <p>Thank you for your order!</p>
    </div>
</body>
</html>
