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
                        <p class="lead text-gray-800 mt-3">List of Completed Service Requests</p>

                        <!-- Service Requests List -->
                        <div class="mt-5">
                            @if(count($services) > 0)
                                @foreach($services as $service)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="order-header mb-0">
                                                Service Request #{{ $service->id }} - {{ $service->created_at->format('d M Y') }}
                                                <span class="badge bg-success">Completed</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- For Admin or Owner: Show User Information -->
                                        @if(in_array(auth()->user()->role_id, [1, 3]))
                                        <strong>Customer Name:</strong> {{ $service->user->fullname }} <br>
                                        <strong>Email:</strong> {{ $service->user->email }} <br>
                                    @endif
                                        <strong>Laptop Model:</strong> {{ $service->laptop_model }} <br>
                                        <strong>Problem Description:</strong> {{ $service->problem_description }} <br>
                                        <strong>Status:</strong> {{ ucfirst($service->status) }} <br>
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
                                <p class="text-muted mt-3">No completed service requests found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
