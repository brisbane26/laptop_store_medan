@extends('layouts.main')

@section('content')
<div class="container">
    <h1 class="mb-4">Laporan Order</h1>

    <!-- Form Pilihan Filter -->
    <form method="GET" action="{{ route('report.index') }}" class="mb-4">
        <div class="d-flex justify-content-start">
            <label for="filter" class="form-label mr-2">Filter:</label>
            <select name="filter" id="filter" class="form-select w-auto">
                <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
            <button type="submit" class="btn btn-primary ml-3">Apply</button>
        </div>
    </form>

    <!-- Tombol Print -->
    <div class="mb-4">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>

    <!-- Tabel Laporan -->
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
            <tr>
                <td>{{ $report['id'] }}</td>
                <td>{{ $report['customer_name'] }}</td>
                <td>
                    @foreach ($report['products'] as $product)
                        {{ $product['name'] }} ({{ $product['quantity'] }})<br>
                    @endforeach
                </td>
                <td>Rp. {{ number_format($report['total_price'], 0, ',', '.') }}</td>
                <td>{{ $report['date'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('css-dependencies')
    <style>
        /* Media query untuk print */
        @media print {
            /* Sembunyikan elemen yang tidak perlu */
            .container h1,
            .container form,
            .container .mb-4 {
                display: none;
            }

            /* Pastikan hanya tabel yang dicetak */
            table {
                page-break-before: always;
            }
        }
    </style>
@endpush

@endsection
