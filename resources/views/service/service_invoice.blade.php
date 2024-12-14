<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Service Invoice #{{ $service->id }}</title>
    <style>
        html, body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }
        h1, h2, h3, h4, h5, h6, p, span, label {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }
        .heading {
            font-size: 24px;
            margin-bottom: 12px;
        }
        .text-start {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        .white-bg {
            background-color: #ffffff;
        }
        .bg-blue {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <table>
        <thead>
            <tr class="white-bg">
                <th colspan="2">
                    <h2 class="text-start">Laptop Store Medan</h2>
                    <p class="text-start">Komp. Garuda Mas No.4, Jl. Berlian I, Deli Tua Barat<br>Zip Code: 20361</p>
                </th>
                <th colspan="2" class="text-end">
                    <span>Invoice ID: #{{ $service->id }}</span><br>
                    <span>Order Date: {{ $service->order_date->format('d M Y') }}</span><br>
                    <span>End Date: {{ $service->end_date ? $service->end_date->format('d M Y') : 'N/A' }}</span>
                </th>
            </tr>
        </thead>
    </table>

    <!-- Customer & Service Details -->
    <table>
        <thead>
            <tr class="bg-blue">
                <th colspan="2">Customer Details</th>
                <th colspan="2">Service Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Customer Name</td>
                <td>{{ $service->user->fullname }}</td>
                <td>Laptop Name</td>
                <td>{{ $service->laptop_model }}</td>
            </tr>
            <tr>
                <td>Phone</td>
                <td>{{ $service->user->phone }}</td>
                <td>Equipments</td>
                <td>{{ $service->equipments }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Description</td>
                <td>{{ $service->problem_description }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Invoice Details -->
    <table>
        <thead>
            <tr class="bg-blue">
                <th>Order Date</th>
                <th>End Date</th>
                <th>Receive Date</th>
                <th>Service Price (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $service->order_date->format('d M Y') }}</td>
                <td>{{ $service->end_date ? $service->end_date->format('d M Y') : 'N/A' }}</td>
                <td>__________________</td> <!-- Manual input by admin -->
                <td>{{ number_format($service->price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer Section -->
    <p class="text-center">
        Thank you for choosing Laptop Store Medan!<br>
        Please keep this invoice as proof of service.
    </p>
</body>
</html>
