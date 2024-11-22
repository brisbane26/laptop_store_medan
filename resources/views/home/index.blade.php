@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/home.css" />
@endpush

{{-- Tambahkan kondisi untuk memuat script jika pengguna adalah admin atau owner --}}

@if(auth()->user()->role->id == 1 || auth()->user()->role->id == 3)
    @push('scripts-dependencies')
        <script src="/js/sales_chart.js"></script>
        <script src="/js/profits_chart.js"></script>
    @endpush
@endif

@section('content')

<div class="mx-3">
    @if(session()->has('message'))
    {!! session("message") !!}
    @endif
</div>

@can('is_admin')
    @include('/partials/home/home_admin')
@elseif(auth()->user()->role->id === 3)
    @include('partials/home/home_owner')
@else
    @include('partials/home/home_customers')
@endcan

@endsection