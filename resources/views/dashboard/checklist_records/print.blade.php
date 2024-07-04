@extends('layouts.app')
@section('title', 'Checklist Records')

@push('title-header', 'Checklist Records')
@push('breadcrumbs')
    <li class="breadcrumb-item active">Checklist Records</li>
@endpush

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
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body bg-transparent border-0 text-dark">
                            <form id="export-form" action="{{ route('checklist-records.export', []) }}">
                                @csrf
                                <input type="hidden" name="type">
                                <div id="datepicker-container">
                                    <div class="row">
                                        <div class="col-6">

                                            <div class="form-group mb-3">
                                                <label for="periode">Periode</label>
                                                <input type="text" class="form-control" id="periode" name="dates"
                                                    placeholder="Select dates or range" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label for="room_id">Ruangan</label>
                                                <select class="@error('room_id') is-invalid @enderror" id="room_id"
                                                    name="room_id" required>
                                                    <option value="">Pilih Ruangan</option>
                                                    @if (request()->filled('room_id'))
                                                        <option value="{{ $room->id }}" selected>{{ $room->name }}
                                                        </option>
                                                    @endif
                                                </select>

                                                @error('room_id')
                                                    <div class="d-block invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="mode">Mode</label>
                                        <select class="form-control w-25" id="mode">
                                            <option value="single" selected>Pilih Tanggal</option>
                                            <option value="range">Rentang</option>
                                        </select>

                                        <button type="button" class="btn btn-success mt-2" id="export-btn">
                                            Export
                                        </button>
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

@section('script')
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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

            $(document).ready(function() {
                var selectedDates = [];
                var isSingleDatePicker = true;

                function updateInput() {
                    $('#periode').val(selectedDates.join(', '));
                }

                function initializeDatepicker() {
                    $('#periode').daterangepicker({
                        singleDatePicker: isSingleDatePicker,
                        showDropdowns: true,
                        autoUpdateInput: false,
                        locale: {
                            format: 'YYYY-MM-DD',
                            cancelLabel: 'Clear',
                        }
                    });

                    $('#periode').on('cancel.daterangepicker', function(ev, picker) {
                        selectedDates = [];
                        updateInput();
                    });

                    $('#periode').on('apply.daterangepicker', function(ev, picker) {
                        if (isSingleDatePicker) {
                            var date = picker.startDate.format('YYYY-MM-DD');
                            if (selectedDates.includes(date)) {
                                selectedDates = selectedDates.filter(d => d !== date);
                            } else {
                                selectedDates.push(date);
                            }
                            updateInput();
                        } else {
                            $('#periode').val(picker.startDate.format('YYYY-MM-DD') + ' - ' +
                                picker.endDate.format('YYYY-MM-DD'));
                        }
                    });

                    $('input[name="type"]').val(isSingleDatePicker ? 'single' : 'range');
                }

                initializeDatepicker();

                $('#mode').change(function() {
                    isSingleDatePicker = !isSingleDatePicker;
                    $('#periode').val('');
                    $('#periode').data('daterangepicker').remove();
                    initializeDatepicker();
                });

                $('#export-btn').click(function() {
                    event.preventDefault();
                    var dates = $('#periode').val();
                    var room_id = $('#room_id').val();
                    var type = isSingleDatePicker ? 'single' : 'range';

                    if (!dates || !room_id) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Pilih periode terlebih dahulu!',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    var url = "/checklist-records/export?type=" + type + "&dates=" + dates +
                        "&room_id=" +
                        room_id;

                    window.open(url, '_blank');
                });
            });
        });
    </script>
@endsection
