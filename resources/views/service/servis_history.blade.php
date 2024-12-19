@extends('layouts.main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/service.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/service.js"></script>
@endpush

@section('content')
<div class="container-fluid px-3">
    <!-- Flasher -->
    @if(session()->has('message'))
        {!! session("message") !!}
    @endif

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid mt-5">
                    <div class="text-center">
                        <h2>Service History</h2>
                        <p class="lead text-gray-800 mt-3">
                            @if($services->contains('status', 'rejected'))
                                List of Rejected Service Requests
                            @else
                                List of Completed Service Requests
                            @endif
                        </p>

                        <!-- Tabel Service Requests -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Service Request #</th>
                                        <th>Date Created</th>
                                        <th>Status</th>
                                        <th>Customer Name</th>
                                        <th>Laptop Model</th>
                                        <th>Equipments</th>
                                        <th>Problem Description</th>
                                        <th>Service Date</th>
                                        <th>Rejection Reason</th>
                                        <th>Laptop Image</th>
                                        <th>Price</th>
                                        <th>Completed Date</th>
                                        <th>Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>#{{ $service->id }}</td>
                                            <td>{{ $service->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if($service->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-success">Completed</span>
                                                @endif
                                            </td>
                                            <td>{{ $service->user->fullname }}</td>
                                            <td>{{ $service->laptop_model }}</td>
                                            <td>{{ $service->equipments }}</td>
                                            <td>{{ $service->problem_description }}</td>
                                            <td>{{ $service->order_date }}</td>
                                            <td>{{ ucfirst($service->rejection_reason) }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $service->laptop_image) }}" alt="Laptop Image" width="100" height="100">
                                            </td>
                                            <td>
                                                @if ($service->price)
                                                    Rp {{ number_format($service->price, 2) }}
                                                @endif
                                            </td>
                                            <td>{{ $service->updated_at->format('d M Y') }}</td>
                                            <td>
                                                @if ($service->status !== 'rejected')
                                                    <a href="{{ route('service.downloadInvoice', $service->id) }}" class="btn btn-primary btn-sm">Download Invoice</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
