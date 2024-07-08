@extends('layouts.auth')
@section('title', 'Pengecekan P3K')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
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
    <div class="">
        <div class="login-logo">
            <a href="{{ route('login', []) }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <div class="row">
                    <div class="col-12">
                        @php
                            $lastChecklistRecord = $checklistRecords
                                ->filter(function ($record) {
                                    return !is_null($record->updated_by);
                                })
                                ->sortByDesc('updated_at')
                                ->first();
                        @endphp
                        @if ($checklistRecordsUnsaved->count() == 0 && !$lastChecklistRecord)
                            <div class="alert bg-warning custom-alert-warning" role="alert">
                                Pada tanggal <b>@date_formatted($dateSelected)</b> ini tidak ada pengecekan yang belum
                                terjadi.
                            </div>
                        @endif
                        @if ($lastChecklistRecord)
                            <div class="alert bg-success custom-alert-success" role="alert">
                                Data terakhir diubah oleh <b>{{ $lastChecklistRecord->updatedBy->name }}</b>
                                pada tanggal
                                <b>@date_formatted_with_hour($lastChecklistRecord->updated_at) WIB</b>
                            </div>
                        @endif
                    </div>

                    @if ($hasDataset)
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-footer">
                                    <h3 class="card-title">
                                        {{ $room->name }} Periode Tanggal @date_formatted($dateSelected)
                                    </h3>
                                </div>
                                <div class="card-body bg-transparent border-0 text-dark">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Item P3K</th>
                                                    <th>Jumlah Standar</th>
                                                    <th>Jumlah Realtime</th>
                                                    <th>Minus</th>
                                                    <th>Status Item</th>
                                                    <th>Nama Petugas</th>
                                                    <th>Catatan</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($checklistRecords as $checklist)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $checklist->item_name }}</td>
                                                        <td>{{ $checklist->item_standard_qty }}</td>
                                                        <td>{{ $checklist->real_qty }}</td>
                                                        <td>{{ $checklist->minus_qty }}</td>
                                                        <td width="150">
                                                            {{ ucfirst($checklist->status) }}
                                                        </td>
                                                        <td>{{ $checklist->updatedByName }}
                                                        </td>
                                                        <td>
                                                            {{ $checklist->note }}
                                                        </td>
                                                        <td width="120">
                                                            {{ $checklist->status_verif == 'verified' ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center">Data tidak ditemukan</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        var urlRoot = '{{ url('/') }}';
        var roomDetail = @json($room);
        var hasDataset = @json($hasDataset);
    </script>
    <script>
        $(document).ready(function() {
            if (hasDataset) {
                $(document).ready(function() {
                    $('#table').DataTable({
                        autoWidth: false,
                        dom: '<"row"<"col-md-6"B><"col-md-6"f>>' +
                            '<"row"<"col-md-6"l><"col-md-6"p>>' +
                            'rt' +
                            '<"row"<"col-md-5"i><"col-md-7"p>>',
                        buttons: [{
                                extend: 'pdf',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                                    format: {
                                        body: function(data, row, column, node) {
                                            // Check if the column should extract value from inputs/selects and textareas
                                            if ([3, 4, 5, 6, 7, 8].indexOf(column) > -1) {
                                                if (column == 7) {
                                                    // textarea html value
                                                    return $(node).find('textarea')
                                                        .text() || '';

                                                }
                                                if (column == 6) {
                                                    // textarea html value
                                                    return $(node).find('input')
                                                        .text() || '';

                                                }
                                                return $(node).find(
                                                        'input, select, textarea').val() ||
                                                    data;
                                            }
                                            return data;
                                        }
                                    }
                                },
                                className: 'btn btn-danger btn-sm',
                                text: '<i class="fas fa-file-pdf"></i>'
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                                    format: {
                                        body: function(data, row, column, node) {
                                            // Check if the column should extract value from inputs/selects and textareas
                                            if ([3, 4, 5, 6, 7, 8].indexOf(column) > -1) {
                                                if (column == 7) {
                                                    // textarea html value
                                                    return $(node).find('textarea')
                                                        .text() || '';

                                                }
                                                if (column == 6) {
                                                    // textarea html value
                                                    return $(node).find('input')
                                                        .text() || '';

                                                }
                                                return $(node).find(
                                                        'input, select, textarea').val() ||
                                                    data;
                                            }
                                            return data;
                                        }
                                    }
                                },
                                className: 'btn btn-success btn-sm ml-1',
                                text: '<i class="fas fa-file-excel"></i>'
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
            }
        });
    </script>
@endsection
