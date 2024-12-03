@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/contact.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/contact.js"></script>
@endpush

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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
            <h1 class="mb-4 text-dark" style="font-weight: bold; font-size: 2.5rem;">Contact Us</h1>
            <p class="lead text-gray-800" style="font-size: 1.2rem;">Konsultasikan Laptop Anda Dengan Kami</p>
            <p class="text-gray-500 mb-2" style="font-size: 1.1rem;">Kami akan melayani anda dengan sepenuh hati.</p>

            <!-- WhatsApp Link -->
            <p class="mt-4" style="font-size: 1.2rem; color: #4CAF50;">
                <i class="fab fa-whatsapp"></i> Click the link below to start chatting with us on WhatsApp:
            </p>
            <a href="https://wa.me/+6281263457021?text=I%20would%20like%20to%20inquire%20about%20your%20products%20and%20services."
               class="btn btn-success text-white mt-3 p-3" style="font-size: 1.1rem; width: 250px; border-radius: 10px; transition: background-color 0.3s ease;"
               target="_blank">
               <i class="fab fa-whatsapp"></i> Contact Us on WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
