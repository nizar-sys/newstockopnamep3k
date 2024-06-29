@extends('layouts.app')
@section('title', 'Tambah Data Pengguna')

@push('title-header', 'Tambah Data Pengguna')
@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Data Pengguna</a></li>
    <li class="breadcrumb-item active">Tambah Data Pengguna</li>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('users.store') }}" method="POST" role="form" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="name">Nama</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Nama Pengguna" value="{{ old('name') }}" name="name">

                                            @error('name')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                                id="email" placeholder="Email Pengguna" value="{{ old('email') }}"
                                                name="email">

                                            @error('email')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="role">Role</label>
                                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                                                @php
                                                    $roles = ['admin', 'atasan', 'petugas'];
                                                    if (auth()->user()->role != 'admin'){
                                                        $roles = ['petugas'];
                                                    }
                                                @endphp
                                                <option value="" selected>---Role---</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role }}" @if (old('role') == $role) selected @endif>
                                                        {{ ucfirst($role) }}</option>
                                                @endforeach
                                            </select>

                                            @error('role')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="password">Katasandi</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                                                placeholder="Katasandi Pengguna" name="password">

                                            @error('password')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="confirmation_password">Konfirmasi katasandi</label>
                                            <input type="password" class="form-control @error('confirmation_password') is-invalid @enderror"
                                                id="confirmation_password" placeholder="Konfirmasi katasandi Pengguna"
                                                name="confirmation_password">

                                            @error('confirmation_password')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="avatar">Avatar</label>
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                        id="avatar" placeholder="Avatar Pengguna"
                                        name="avatar">

                                    @error('avatar')
                                        <div class="d-block invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                                        <a href="{{route('users.index')}}" class="btn btn-sm btn-secondary">Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
