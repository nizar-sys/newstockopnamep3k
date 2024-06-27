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
                    $properties = collect($activityLog->properties)
                        ->only($sortedKeys)
                        ->sortBy(function ($value, $key) use ($sortedKeys) {
                            return array_search($key, $sortedKeys);
                        });
                @endphp

                @foreach ($properties as $key => $property)
                    <div class="col-md-12 col-sm-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <h3 class="card-title text-dark">{{ ucfirst($key) }}</h3>
                            </div>
                            <div class="card-footer border-0 text-dark">
                                <div class="table-responsive">
                                    @if (is_array($property) && !empty($property) && is_array(reset($property)))
                                        <table class="table table-striped table-data">
                                            <thead>
                                                <tr>
                                                    @foreach (array_keys(reset($property)) as $header)
                                                        <th>{{ ucfirst($header) }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($property as $item)
                                                    <tr>
                                                        @foreach ($item as $value)
                                                            <td>
                                                                @if (is_array($value))
                                                                    @php
                                                                        $jsonValue = json_encode(
                                                                            $value,
                                                                            JSON_PRETTY_PRINT,
                                                                        );
                                                                        $decodedValue = json_decode($jsonValue, true);
                                                                    @endphp
                                                                    {{ $decodedValue['name'] ?? $jsonValue }}
                                                                @else
                                                                    {{ $value }}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <pre>{{ json_encode($property, JSON_PRETTY_PRINT) }}</pre>
                                    @endif
                                </div>
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

@section('script')
    <script>
        $(document).ready(function() {
            $('.table-data').DataTable({
                autoWidth: false,
                dom: '<"row"<"col-md-6"B><"col-md-6"f>>' +
                    '<"row"<"col-md-6"l><"col-md-6"p>>' +
                    'rt' +
                    '<"row"<"col-md-5"i><"col-md-7"p>>',
                buttons: [{
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf"></i>',
                        title: '{{ $activityLog->description }}'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success btn-sm ml-1',
                        text: '<i class="fas fa-file-excel"></i>',
                        title: '{{ $activityLog->description }}'
                    },
                ],
                language: {
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });
        });
    </script>
@endsection
