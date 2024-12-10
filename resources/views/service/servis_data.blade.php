@extends('layouts.main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/service.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/service.js"></script>
@endpush

@section('content')
<div class="container-fluid px-3">
    <!-- flasher -->
    @if(session()->has('message'))
        {!! session("message") !!}
    @endif

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid mt-5">
                    <div class="text-center">
                        <h2>Service Request</h2>
                        <p class="lead text-gray-800 mt-3">List of All Service Requests</p>

                        <!-- Service Requests List -->
                        <div class="mt-5">
                            @if(count($services) > 0)
                            @foreach($services as $service)
                            @if($service->status != 'done')
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="order-header mb-0">
                                                Service Request #{{ $service->id }} - {{ $service->created_at->format('d M Y') }}
                                                <span class="badge bg-{{ $service->status == 'approved' ? 'success' : ($service->status == 'in-progress' ? 'warning' : ($service->status == 'ready-to-pickup' ? 'info' : 'secondary')) }}">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </h5>
                                            @if (auth()->user()->role_id == 1 && $service->status != 'completed')
                                            <div class="d-flex">
                                                @if ($service->status == 'pending')
                                                    <!-- Tombol untuk menyetujui service -->
                                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $service->id }}">
                                                        Approve
                                                    </button>
                                                @elseif ($service->status == 'approved' || $service->status == 'in-progress' || $service->status == 'ready-to-pickup')
                                                    <!-- Form untuk mengubah status service -->
                                                    <form method="post" action="{{ route('services.updateStatus', $service) }}" class="me-2">
                                                        @csrf
                                                        <select name="status" class="form-select" onchange="this.form.submit()">
                                                            <option value="">Status</option>
                                                            <option value="in-progress" {{ $service->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                                            <option value="ready-to-pickup" {{ $service->status == 'ready-to-pickup' ? 'selected' : '' }}>Ready to Pickup</option>
                                                            @if ($service->status == 'ready-to-pickup')
                                                                <option value="done" {{ $service->status == 'done' ? 'selected' : '' }}>Done</option>
                                                            @endif
                                                        </select>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif 
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <strong>Laptop Model:</strong> {{ $service->laptop_model }} <br>
                                        <strong>Problem Description:</strong> {{ $service->problem_description }} <br>
                                        <strong>Status:</strong> {{ ucfirst($service->status) }} <br>

                                        <!-- Price input if status is approved -->
                                        @if ($service->status == 'approved' && !$service->price)
                                            <form action="{{ route('service.setPrice', $service) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Price</label>
                                                    <input type="number" class="form-control" id="price" name="price" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Set Price</button>
                                            </form>
                                        @elseif ($service->price)
                                            <strong>Price: </strong> Rp {{ number_format($service->price, 2) }} <br>
                                        @endif
                                    </div>

                                    <div class="card-footer text-muted">
                                        Created on: {{ $service->created_at->format('d M Y') }}
                                    </div>
                                </div>

                                <!-- Modal for Approving Service Request -->
                                <div class="modal fade" id="approveModal{{ $service->id }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="approveModalLabel">Approve Service Request #{{ $service->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.services.approve', $service) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="price" class="form-label">Set Price</label>
                                                        <input type="number" class="form-control" id="price" name="price" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Approve</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            @else
                                <p class="text-muted mt-3">No service requests found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
