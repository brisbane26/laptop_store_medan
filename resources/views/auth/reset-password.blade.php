@extends('layouts.auth')

@push('css-dependencies')
<link href="/css/auth.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Reset Kata Sandi</h1>
                                </div>

                                @if(session()->has('message'))
                                <div class="alert alert-info">{{ session('message') }}</div>
                                @endif

                                <form class="user" method="post" action="{{ route('auth.reset-password-post') }}">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $email }}">

                                    <div class="form-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                          id="password" name="password" placeholder="Masukkan kata sandi baru">
                                        @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi kata sandi baru">
                                    </div>
                                    <button type="submit" class="btn btn-info btn-block">Reset Kata Sandi</button>
                                </form>

                                <hr>
                                <div class="text-center">
                                    <a class="small" href="/auth/login">Kembali ke login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
