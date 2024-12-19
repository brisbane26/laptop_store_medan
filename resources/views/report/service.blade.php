@extends('layouts.main')

@section('content')
<div class="container">
    <h1 class="mb-4">Service Report</h1>

    <!-- Form Pilihan Filter -->
    <form method="GET" action="{{ route('report.service') }}" class="mb-4">
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

    <!-- Tabel Laporan Service -->
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Service ID</th>
                <th>Customer Name</th>
                <th>Laptop Model</th>
                <th>Problem Description</th>
                <th>Price</th>
                <th>Order Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
            <tr>
                <td>{{ $service['id'] }}</td>
                <td>{{ $service['customer_name'] }}</td>
                <td>{{ $service['laptop_model'] }}</td>
                <td>{{ $service['problem_description'] }}</td>
                <td>Rp. {{ number_format($service['price'], 0, ',', '.') }}</td>
                <td>{{ $service['order_date'] }}</td>
                <td>{{ $service['end_date'] }}</td>
                <td>
                    @if ($service['status'] == 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-success">Completed</span>
                    @endif
                </td>
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
            .container .mb-4 {
                display: none;
            }

            /* Pastikan tabel berada di halaman pertama */
            table {
                page-break-before: always;
            }
        }
    </style>
@endpush

@endsection
