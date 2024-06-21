@extends('layouts.auth')
@section('title', 'Register')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('register', []) }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">

                <form role="form" action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="input-group mb-3">
                        <input class="form-control" name="name" placeholder="Nama" type="text"
                            value="{{ old('name') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">*{{ $message }}</div>
                        @enderror
                    </div>

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
                        <input class="form-control" name="password" placeholder="Katasandi" type="password"
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
                        <input class="form-control" name="password_confirmation" placeholder="Konfirmasi Katasandi" type="password"
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
                        <div class="col-8">
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    Sudah punya akun ? login di <a href="{{ route('password.request') }}">sini.</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
