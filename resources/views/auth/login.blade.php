@extends('layouts.auth')
@section('title', 'Login')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <small>Sistem Informasi Pengecekan Kotak P3K</small> <br>
            <img src="{{ asset('/uploads/images/logo-auth.png') }}" alt="AdminLTE Logo" class="img-fluid w-25">
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">

                <form role="form" action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="input-group mb-3">
                        <input class="form-control" name="email" placeholder="Email" type="email"
                            value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">*{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input class="form-control" name="password" placeholder="Password" type="password"
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

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input class="custom-control-input" name="remember" id="remember" type="checkbox">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="{{ route('password.request') }}">Lupa Kata Sandi</a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('register', []) }}" class="text-center">Buat akun petugas</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
