@extends('layouts.app')
@section('title', 'Log Aktivitas')

@push('title-header', 'Log Aktivitas ' . $activityLog->description)
@push('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('activity-logs.index') }}">Log Aktivitas</a></li>
<li class="breadcrumb-item active">Detil Log Aktivitas</li>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @php
                    $sortedKeys = ['old', 'new', 'changes'];
                    $properties = collect($activityLog->properties)->sortBy(function ($value, $key) use ($sortedKeys) {
                        return array_search($key, $sortedKeys);
                    });
                @endphp

                @foreach ($properties as $key => $property)
                    <div class="col-md-4 col-sm-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <h3 class="card-title text-dark">{{ ucfirst($key) }}</h3>
                            </div>
                            <div class="card-footer border-0 text-dark">
                                <pre>{{ json_encode($property, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-6">
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
