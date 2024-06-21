@extends('layouts.app')
@section('title', 'Tambah Data Ruangan')

@push('title-header', 'Tambah Data Ruangan')
@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Data Ruangan</a></li>
    <li class="breadcrumb-item active">Tambah Data Ruangan</li>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('rooms.store') }}" method="POST" role="form" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="name">Nama Ruangan</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Nama Ruangan" value="{{ old('name') }}" name="name">

                                            @error('name')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                                        <a href="{{route('rooms.index')}}" class="btn btn-sm btn-secondary">Kembali</a>
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
