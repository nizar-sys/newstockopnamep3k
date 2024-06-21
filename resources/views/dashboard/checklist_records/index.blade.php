@extends('layouts.app')
@section('title', 'Checklist Records')

@push('title-header', 'Checklist Records')
@push('breadcrumbs')
    <li class="breadcrumb-item active">Checklist Records</li>
@endpush


@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body bg-transparent border-0 text-dark">
                            <form action="" method="get">
                                @csrf

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="date">Periode</label>
                                            <input type="text" class="form-control @error('date') is-invalid @enderror"
                                                id="date" placeholder="Periode" value="{{ old('date') }}"
                                                name="date">

                                            @error('date')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="room_id">Ruangan</label>
                                            <input type="text" class="form-control @error('room_id') is-invalid @enderror"
                                                id="room_id" placeholder="Ruangan" value="{{ old('room_id') }}"
                                                name="room_id">

                                            @error('room_id')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-sm btn-primary btn-block">cek item</button>
                                        <a href="{{route('checklist-records.index')}}" class="btn btn-sm btn-secondary btn-block">Reset</a>
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
