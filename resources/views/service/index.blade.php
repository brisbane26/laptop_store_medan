@extends('layouts.main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/service.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/service.js"></script>
@endpush

@section('content')
<div class="container py-5">
    {{-- <!-- Flasher -->
    @if(session()->has('message'))
    <script>
        window.serviceMessage = @json(session('message'));
    </script>
    @endif --}}

    <div class="text-center mb-5">
        <h2 class="fw-bold">Service Booking</h2>
        <p class="lead text-muted">Create a Service Request for Your Laptop</p>
        <p class="text-secondary">Provide the details below to create a service request</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('services.store') }}" method="POST" id="service_booking_form" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="laptop_model" class="form-label">Laptop Model</label>
                            <input type="text" class="form-control" id="laptop_model" name="laptop_model" placeholder="Enter your laptop model" required>
                        </div>

                        <div class="mb-3">
                            <label for="equipments" class="form-label">Equipments</label>
                            <textarea class="form-control" id="equipments" name="equipments" rows="3" placeholder="List additional equipment (e.g., charger, bag)"></textarea>
                            <small class="form-text text-muted">Optional</small>
                        </div>

                        <div class="mb-3">
                            <label for="laptop_image" class="form-label">Laptop Image</label>
                            <input type="file" class="form-control" id="laptop_image" name="laptop_image" accept="image/*">
                            <small class="form-text text-muted">Upload an image of your laptop (optional).</small>
                        </div>

                        <div class="mb-3">
                            <label for="problem_description" class="form-label">Problem Description</label>
                            <textarea class="form-control" id="problem_description" name="problem_description" rows="4" placeholder="Describe the problem" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="order_date" class="form-label">Service Date</label>
                            <input type="date" class="form-control" id="order_date" name="order_date" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit Service Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
