@extends('layouts.auth')
@section('title', 'Beranda')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }
    </style>
@endpush


@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('login', []) }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <form role="form" action="">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label for="periode">Periode</label>
                                    <div class="input-group mb-3">
                                        @php
                                            \Carbon\Carbon::setLocale('id');
                                            $valueDate = request()->filled('date')
                                                ? $dateSelected
                                                : \Carbon\Carbon::now()->format('Y-m-d');
                                        @endphp
                                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                                            id="date" placeholder="Periode" value="{{ old('date', $valueDate) }}"
                                            name="date" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                            </div>
                                        </div>
                                        @error('periode')
                                            <div class="invalid-feedback d-block">*{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="periode">Ruangan</label>
                                    <div class="input-group mb-3">
                                        <select class="@error('room_id') is-invalid @enderror" id="room_id" name="room_id"
                                            required>
                                            <option value="">Pilih Ruangan</option>
                                            @if ($room)
                                                <option value="{{ $room->id }}" selected>{{ $room->name }}
                                                </option>
                                            @endif
                                        </select>

                                        @error('room_id')
                                            <div class="invalid-feedback d-block">*{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Generate Code</button>
                                    <a href="{{ url('/', []) }}" class="btn btn-secondary btn-block">Reset</a>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>

                    @if ($room)
                        <div class="col-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title text-capitalize">
                                        {{ $room->name }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <p class="text-bold">Nama Ruangan</p>
                                            <p>{{ $room->name }}</p>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <p class="text-bold">Total Item P3K</p>
                                            <p>{{ $room->items->count() }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <p>{{ $qrCode }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        var urlRoot = '{{ url('/') }}';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function initializeSelect2(element, url) {
            element.select2({
                ajax: {
                    url: urlRoot + url,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(product) {
                                return {
                                    id: product.id,
                                    text: product.name
                                };
                            })
                        };
                    },
                    cache: true,
                },
                width: '100%',
            });
        }

        $(document).ready(function() {
            initializeSelect2($('#room_id'), '/api/rooms');
        });
    </script>
@endsection
