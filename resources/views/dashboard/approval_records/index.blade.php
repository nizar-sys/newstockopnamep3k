@extends('layouts.app')
@section('title', 'Approval Records')

@push('title-header', 'Approval Records')
@push('breadcrumbs')
    <li class="breadcrumb-item active">Approval Records</li>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        @if ($approvalRecords->count() == 0)
                            <div class="card-header">
                                <div class="alert bg-warning custom-alert-warning" role="alert">
                                    Tidak ada data yang perlu diverifikasi!
                                </div>
                            </div>
                        @endif
                        <div class="card-footer">
                            <div class="card-tools">
                                <a href="#" class="btn btn-sm btn-warning float-right edit-dataset ml-2">Edit</a>
                                <button class="btn btn-sm btn-success float-right d-none verif-all-data"><i
                                        class="fas fa-check"></i> Verifikasi</button>
                            </div>
                        </div>
                        <div class="card-body bg-transparent border-0 text-dark">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="check-all" disabled>
                                            </th>
                                            <th>No</th>
                                            <th>Detail Waktu</th>
                                            <th>Detail Ruangan</th>
                                            <th>Diubah Oleh</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($approvalRecords as $record)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="checklist_record_id[]"
                                                        value="{{ $record->id }}" class="check-data" disabled>
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>@date_formatted($record->created_at)</td>
                                                <td>
                                                    {{ $record->item->room->name }}
                                                </td>
                                                <td>
                                                    {{ $record->updatedBy->name }}
                                                </td>
                                                <td>
                                                    {{ $record->status_verif == 'unverified' ? 'Belum diverifikasi' : 'Terverifikasi' }}
                                                </td>
                                                <td class="d-flex jutify-content-center">
                                                    <button onclick="approveForm('{{ $record->id }}')"
                                                        class="btn btn-sm btn-success ml-1"><i class="fas fa-check"></i>
                                                        Verifikasi</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        const urlRoot = '{{ url('/') }}';
    </script>
    <script>
        function approveForm(checklistId) {
            Swal.fire({
                title: 'Verifikasi data',
                text: "Anda akan memverifikasi data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: urlRoot + '/approval-records/approve',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            checklist_record_id: checklistId
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message || 'Data berhasil terverifikasi!',
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
            })
        }

        $(document).ready(function() {
            $('.verif-all-data').click(function() {
                let checklistRecordId = [];
                $('.check-data').each(function() {
                    if ($(this).is(':checked')) {
                        checklistRecordId.push($(this).val());
                    }
                });

                if (checklistRecordId.length > 0) {
                    Swal.fire({
                        title: 'Verifikasi data',
                        text: "Anda akan memverifikasi data!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Batal!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: urlRoot + '/approval-records/approve',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    checklist_record_id: checklistRecordId
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Success',
                                        text: response.message ||
                                            'Data berhasil terverifikasi!',
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
                                        text: xhr.responseJSON.message ||
                                            'Terjadi kesalahan!',
                                        icon: 'error',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'OK'
                                    });
                                }

                            });
                        }
                    })
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Pilih minimal satu data!',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });

            $('.check-data').click(function() {
                let isCheck = false;
                $('.check-data').each(function() {
                    if ($(this).is(':checked')) {
                        isCheck = true;
                    }
                });

                if (isCheck) {
                    $('#verif-all-data').removeClass('d-none');
                } else {
                    $('#verif-all-data').addClass('d-none');
                }

                if ($('.check-data').length == $('.check-data:checked').length) {
                    $('#check-all').prop('checked', true);
                } else {
                    $('#check-all').prop('checked', false);
                }
            });

            $('#check-all').click(function() {
                $('.check-data').prop('checked', $(this).is(':checked'));

                if ($('.check-data').length == $('.check-data:checked').length) {
                    $('#verif-all-data').removeClass('d-none');
                } else {
                    $('#verif-all-data').addClass('d-none');
                }
            });

            $('#table').DataTable({
                autoWidth: false,
                dom: '<"row"<"col-md-6"B><"col-md-6"f>>' +
                    '<"row"<"col-md-6"l><"col-md-6"p>>' +
                    'rt' +
                    '<"row"<"col-md-5"i><"col-md-7"p>>',
                buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                        },
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf"></i> Cetak PDF'
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                        },
                        className: 'btn btn-success btn-sm ml-1',
                        text: '<i class="fas fa-file-excel"></i> Cetak Excel'
                    },
                ],
                language: {
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                pageLength: 50,
            });

            $('.edit-dataset').on('click', function(event) {
                event.preventDefault();

                $('.edit-dataset').text(function(i, text) {
                    return text === 'Edit' ? 'Cancel' : 'Edit';
                });

                $('.verif-all-data').toggleClass('d-none');

                $('input, select, textarea').each(function() {
                    $(this).prop('disabled', function(i, v) {
                        return !v;
                    });
                });

                // $('.check-data').prop('checked', function(i, v) {
                //     return !v;
                // });
            });
        });
    </script>
@endsection
