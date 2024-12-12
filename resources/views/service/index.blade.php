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
    <script>
        window.serviceMessage = @json(session('message'));
    </script>
    @endif    

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid mt-5">
                    <div class="text-center">
                        <h2>Service Booking</h2>
                        <p class="lead text-gray-800 mt-3">Create a Service Request for Your Laptop</p>
                        <p class="text-gray-500 mb-2 mt-3">Provide the details below to create a service request</p>
                        
                        <!-- Form to Create Service Booking -->
                        <form action="{{ route('services.store') }}" method="POST" id="service_booking_form">
                            @csrf
                            <div class="form-group">
                                <label for="laptop_model">Laptop Model</label>
                                <input type="text" class="form-control" id="laptop_model" name="laptop_model" required>
                            </div>
                            <div class="form-group">
                                <label for="problem_description">Problem Description</label>
                                <textarea class="form-control" id="problem_description" name="problem_description" rows="4" required></textarea>
                            </div>
                        
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Submit Service Request</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
