@extends('layouts.app')
@section('title', 'Checklist Records')

@push('title-header', 'Checklist Records')
@push('breadcrumbs')
    <li class="breadcrumb-item active">Checklist Records</li>
@endpush

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
    <section class="content">
        <div class="container-fluid">
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
                            Pada tanggal <b>@date_formatted($dateSelected)</b> ini tidak ada pengecekan yang belum terjadi.
                        </div>
                    @endif
                    @if ($lastChecklistRecord)
                        <div class="alert bg-success custom-alert-success" role="alert">
                            Data terakhir diubah oleh <b>{{ $lastChecklistRecord->updatedBy->name }}</b> pada tanggal
                            <b>@date_formatted_with_hour($lastChecklistRecord->updated_at) WIB</b>
                        </div>
                    @endif
                    <div class="card shadow">
                        <div class="card-body bg-transparent border-0 text-dark">
                            <form action="" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="date">Periode</label>
                                            @php
                                                \Carbon\Carbon::setLocale('id');
                                                $valueDate = request()->filled('date')
                                                    ? $dateSelected
                                                    : \Carbon\Carbon::now()->format('Y-m-d');
                                            @endphp
                                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                                id="date" placeholder="Periode" value="{{ old('date', $valueDate) }}"
                                                name="date" required>

                                            @error('date')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
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

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-sm btn-primary btn-block">Cek</button>
                                        <a href="{{ route('checklist-records.index') }}"
                                            class="btn btn-sm btn-secondary btn-block">Reset</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                @if ($hasDataset)
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-footer">
                                <h3 class="card-title">
                                    {{ $room->name }} Periode Tanggal @date_formatted($dateSelected)
                                </h3>

                                <div class="card-tools">
                                    <a href="#"
                                        class="btn btn-sm btn-primary float-right save-dataset ml-2 d-none">Simpan Data</a>
                                    <a href="#" class="btn btn-sm btn-warning float-right edit-dataset ml-2">Edit</a>
                                    <a href="#" class="btn btn-sm btn-success float-right add-item">Tambah Item</a>
                                </div>
                            </div>
                            <form action="{{ route('checklist-records.store') }}" method="post" id="store-dataset">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="date" value="{{ $dateSelected }}">

                                <div class="card-body bg-transparent border-0 text-dark">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        #
                                                    </th>
                                                    <th>No</th>
                                                    <th>Item P3K</th>
                                                    <th>Jumlah Standar</th>
                                                    <th>Jumlah Realtime</th>
                                                    <th>Minus</th>
                                                    <th>Status Item</th>
                                                    <th>Nama petugas</th>
                                                    <th>Catatan</th>
                                                    <th>Status Verifikasi</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($checklistRecords as $checklist)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="item_id[]"
                                                                value="{{ $checklist->item_id }}" class="check-data">
                                                        </td>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $checklist->item_name }}</td>
                                                        <td>{{ $checklist->item_standard_qty }}</td>
                                                        <td>
                                                            <input type="number" data-item_id="{{ $checklist->item_id }}"
                                                                data-updated_by="{{ auth()->id() }}" name="real_qty[]"
                                                                class="form-control" disabled
                                                                value="{{ $checklist->real_qty }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="minus_qty[]" class="form-control"
                                                                disabled value="{{ $checklist->minus_qty }}">
                                                        </td>
                                                        <td width="150">
                                                            <select name="status[]" class="form-control" disabled>
                                                                @foreach (['layak', 'kadaluarsa'] as $status)
                                                                    <option value="{{ $status }}"
                                                                        {{ $status == $checklist->status ? 'selected' : '' }}>
                                                                        {{ ucfirst($status) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="name[]" class="form-control"
                                                                disabled value="{{ $checklist->updatedByName }}">
                                                        </td>
                                                        <td>
                                                            <textarea name="note[]" cols="15" rows="1" disabled>{{ $checklist->note }}</textarea>
                                                        </td>
                                                        <td width="120">
                                                            {{ $checklist->status_verif == 'verified' ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                                        </td>
                                                        <td class="d-flex justify-content-center">
                                                            <button
                                                                onclick="updateItem('{{ $checklist->item_name }}', '{{ $checklist->item_id }}', '{{ $checklist->item_standard_qty }}')"
                                                                class="btn btn-sm btn-warning ml-1"><i
                                                                    class="fas fa-pencil-alt"></i></button>
                                                            <form id="delete-form-{{ $checklist->item_id }}"
                                                                action="{{ route('checklist-records.item.destroy', $checklist->item_id) }}"
                                                                class="d-none" method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button type="button"
                                                                onclick="deleteItem('{{ route('checklist-records.item.destroy', $checklist->item_id) }}')"
                                                                class="btn btn-sm btn-danger ml-1"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>

                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center">Data tidak ditemukan</td>
                                                    </tr>
                                                @endforelse
                                        </table>
                                    </div>
                                </div>
                            </form>
                            <div class="card-footer">
                                <div class="card-tools">
                                    <a href="#" class="btn btn-sm btn-success float-left add-item" add-item>Tambah
                                        Item</a>
                                    <a href="#" class="btn btn-sm btn-warning float-left edit-dataset ml-2">Edit</a>
                                    <a href="#"
                                        class="btn btn-sm btn-primary float-left save-dataset ml-2 d-none">Simpan Data</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        var urlRoot = '{{ url('/') }}';
        var roomDetail = @json($room);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function deleteItem(url) {
            Swal.fire({
                title: 'Hapus data',
                text: "Anda akan menghapus data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal!'
            }).then((result) => {
                $.ajax({
                    url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success',
                            text: response.message || 'Data berhasil dihapus!',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON.message || 'Terjadi kesalahan!',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }

                });
            })
        }

        function updateItem(itemName, itemId, itemQty) {
            event.preventDefault();
            var urlUpdateItem = urlRoot + '/checklist-records/items/' + itemId;
            const modal = document.createElement('div');
            modal.classList.add('modal');
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Item ${itemName}</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="${urlUpdateItem}" method="post">
                            <div class="modal-body">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Item P3K</label>
                                    <input type="text" name="name" class="form-control" value="${itemName}" required>
                                </div>
                                <div class="form-group">
                                    <label for="standard_qty">Jumlah Standar</label>
                                    <input type="number" name="standard_qty" class="form-control" value="${itemQty}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            $(modal).modal('show');

            $(modal).on('hidden.bs.modal', function() {
                document.body.removeChild(modal);
            });
        }

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

        function addItem() {
            event.preventDefault();

            const modal = document.createElement('div');
            modal.classList.add('modal');
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Item ${roomDetail.name}</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('checklist-records.item.store') }}" method="post">
                            <input type="hidden" name="room_id" value="${roomDetail.id}">
                            <input type="hidden" name="date" value="{{ $dateSelected }}">
                            <div class="modal-body">
                                @csrf
                                <div class="form-group
                                    <label for="name">Item P3K</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group
                                    <label for="standard_qty">Jumlah Standar</label>
                                    <input type="number" name="standard_qty" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Tambah Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            $(modal).modal('show');

            $(modal).on('hidden.bs.modal', function() {
                document.body.removeChild(modal);
            });
        }

        function saveDataset() {
            var data = [];

            $('input.check-data:checked').each(function() {
                var row = $(this).closest('tr');
                var realQty = row.find('input[name="real_qty[]"]').val();
                var itemID = row.find('input[name="real_qty[]"]').data('item_id');
                var updatedBy = row.find('input[name="real_qty[]"]').data('updated_by');
                var minusQty = row.find('input[name="minus_qty[]"]').val();
                var status = row.find('select[name="status[]"]').val();
                var name = row.find('input[name="name[]"]').val();
                var note = row.find('textarea[name="note[]"]').val();

                data.push({
                    item_id: itemID,
                    real_qty: realQty,
                    updated_by: updatedBy,
                    minus_qty: minusQty,
                    status: status,
                    name: name,
                    note: note
                });
            });

            $.ajax({
                url: '{{ route('checklist-records.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    room_id: roomDetail.id,
                    date: '{{ $dateSelected }}',
                    data: data
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Success',
                        text: response.message || 'Data berhasil disimpan!',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 1000);

                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Terjadi kesalahan!',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        $(document).ready(function() {
            initializeSelect2($('#room_id'), '/api/rooms');

            $('.check-data').on('change', function() {
                if ($('.check-data:checked').length > 0) {
                    $('.save-dataset').removeClass('d-none');
                } else {
                    $('.save-dataset').addClass('d-none');
                }

                var row = $(this).closest('tr');

                row.find('select, textarea, input[type="number"]').each(function() {
                    $(this).prop('disabled', !$(this).prop('disabled'));
                });
            });

            $('.edit-dataset').on('click', function(event) {
                event.preventDefault();

                $('.edit-dataset').text(function(i, text) {
                    return text === 'Edit' ? 'Cancel' : 'Edit';
                });

                $('.save-dataset').toggleClass('d-none');

                $('input[type="number"], select, textarea').each(function() {
                    $(this).prop('disabled', function(i, v) {
                        return !v;
                    });
                });

                $('.check-data').prop('checked', function(i, v) {
                    return !v;
                });
            });

            $('.add-item').on('click', addItem);

            $('.save-dataset').on('click', function() {
                event.preventDefault();

                saveDataset();
            });
        });
    </script>
@endsection
