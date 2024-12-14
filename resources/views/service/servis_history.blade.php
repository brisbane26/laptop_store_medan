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
                            <!-- Tampilkan judul berbeda tergantung status -->
                            @if($services->contains('status', 'rejected'))
                                List of Rejected Service Requests
                            @else
                                List of Completed Service Requests
                            @endif
                        </p>

                        <!-- Service Requests List -->
                        <div class="mt-5">
                            @if(count($services) > 0)
                                @foreach($services as $service)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="order-header mb-0">
                                                    Service Request #{{ $service->id }} - {{ $service->created_at->format('d M Y') }}
                                                    <!-- Tampilkan badge sesuai status -->
                                                    @if($service->status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-success">Completed</span>
                                                    @endif
                                                </h5>
                                                @if ($service->status !== 'rejected')
                                                <div class="card-footer text-muted">
                                                    Completed on: {{ $service->updated_at->format('d M Y') }}
                                                    <a href="{{ route('service.downloadInvoice', $service->id) }}" class="btn btn-primary btn-sm float-right">Download Invoice</a>
                                                </div>
                                                @endif
                                                
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- For Admin or Owner: Show User Information -->
                                            @if(in_array(auth()->user()->role_id, [1, 3]))
                                                <strong>Customer Name:</strong> {{ $service->user->fullname }} <br>
                                                <strong>Email:</strong> {{ $service->user->email }} <br>
                                            @endif
                                            <strong>Laptop Model:</strong> {{ $service->laptop_model }} <br>
                                            <strong>Equipments:</strong> {{ $service->equipments }} <br>
                                            <strong>Problem Description:</strong> {{ $service->problem_description }} <br>
                                            <strong>Service Date:</strong> {{ $service->order_date }} <br>
                                            <strong>Rejection reason:</strong> {{ ucfirst($service->rejection_reason) }} <br>
                                            <strong>Laptop image:</strong> <br>
                                            <img src="{{ asset('storage/' . $service->laptop_image) }}" alt="Laptop Image" width="100" height="100"><br>
                                            @if ($service->price)
                                                <strong>Price: </strong> Rp {{ number_format($service->price, 2) }} <br>
                                            @endif
                                        </div>
                                        <div class="card-footer text-muted">
                                            Completed on: {{ $service->updated_at->format('d M Y') }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mt-3">No completed or rejected service requests found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
