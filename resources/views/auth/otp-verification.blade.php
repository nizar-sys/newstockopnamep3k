@extends('layouts.auth')
@section('title', 'Verify Otp')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <small>Sistem Informasi Pengecekan Kotak P3K</small> <br>
            <img src="{{ asset('/uploads/images/logo-auth.png') }}" alt="AdminLTE Logo" class="img-fluid w-25">
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">

                <form role="form" action="{{ route('otp.validation') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{$type}}">
                    <input type="hidden" name="email" value="{{request()->email}}">

                    <div class="input-group mb-3">
                        <input class="form-control" name="otp" placeholder="Kode OTP" type="text"
                            value="{{ old('otp') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('otp')
                            <div class="invalid-feedback d-block">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-8">
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Verifikasi</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
