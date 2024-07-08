@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <small>Sistem Informasi Pengecekan Kotak P3K</small> <br>
            <img src="{{ asset('/uploads/images/logo-auth.png') }}" alt="AdminLTE Logo" class="img-fluid w-25">
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">

                <form role="form" action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{request()->token}}">

                    <div class="input-group mb-3">
                        <input class="form-control" name="email" placeholder="Email" type="text"
                            value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input class="form-control" name="password" placeholder="Kata Sandi" type="password"
                            value="{{ old('password') }}" id="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                        @error('password')
                            <div class="invalid-feedback d-block">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input class="form-control" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" type="password"
                            value="{{ old('password_confirmation') }}" id="password_confirmation">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Reset Kata Sandi</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="{{ route('login', []) }}">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
