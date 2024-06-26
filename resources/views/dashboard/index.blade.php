@extends('layouts.app')
@section('title', 'Dashboard')
@php
    $auth = Auth::user();
@endphp

@push('title-header')
    Dashboard
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-12">

                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $data['count_user'] }}</h3>
                            <p>Total Pengguna</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('users.index', []) }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-12">

                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $data['count_room'] }}</h3>
                            <p>Total Ruangan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <a href="{{ route('rooms.index', []) }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-12">

                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $data['count_approval'] }}</h3>
                            <p>Total Pending Approval</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <a href="{{ route('checklist-records.index', []) }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
