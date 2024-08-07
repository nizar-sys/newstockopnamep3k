@extends('layouts.app')
@section('title', 'Data Ruangan')

@push('title-header', 'Data Ruangan')
@push('breadcrumbs')
    <li class="breadcrumb-item active">Data Ruangan</li>
@endpush

@push('action_btn')
    <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-footer">
                            <div class="card-tools">
                                @stack('action_btn')
                            </div>
                        </div>
                        <div class="card-body bg-transparent border-0 text-dark">
                            <div class="table-responsive">
                                <table class="table table-flush table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Ruangan</th>
                                            <th>Kode QR</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rooms as $room)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $room->name }}</td>
                                                <td>
                                                    @if ($room->last_changes_date)
                                                        @php
                                                            $routeUrl = route('landing.checklist', [
                                                                'room_id' => $room->id,
                                                                'date' => $room->last_changes_date,
                                                            ]);

                                                            $qrCode = base64_encode(
                                                                QrCode::format('png')->size(100)->generate($routeUrl),
                                                            );
                                                            $downloadQrcode = base64_encode(
                                                                QrCode::format('png')->size(300)->generate($routeUrl),
                                                            );
                                                        @endphp

                                                        <a href="data:image/png;base64,{{ $downloadQrcode }}"
                                                            download="QRCode_{{ $room->name }}.png"
                                                            rel="noopener noreferrer">
                                                            <img src="data:image/png;base64,{{ $qrCode }}"
                                                                alt="QR Code">
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="d-flex jutify-content-center">
                                                    @if ($room->last_changes_date)
                                                        <a href="data:image/png;base64,{{ $downloadQrcode }}"
                                                            download="QRCode_{{ $room->name }}.png"
                                                            rel="noopener noreferrer" class="btn btn-sm btn-success">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('rooms.show', $room->id) }}"
                                                        class="btn btn-sm btn-primary ml-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-list-columns-reverse" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                d="M0 .5A.5.5 0 0 1 .5 0h2a.5.5 0 0 1 0 1h-2A.5.5 0 0 1 0 .5Zm4 0a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1h-10A.5.5 0 0 1 4 .5Zm-4 2A.5.5 0 0 1 .5 2h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Zm4 0a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5Zm-4 2A.5.5 0 0 1 .5 4h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Zm4 0a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5Zm-4 2A.5.5 0 0 1 .5 6h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Zm4 0a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1h-8a.5.5 0 0 1-.5-.5Zm-4 2A.5.5 0 0 1 .5 8h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Zm4 0a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1h-8a.5.5 0 0 1-.5-.5Zm-4 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Zm4 0a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1h-10a.5.5 0 0 1-.5-.5Zm-4 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Zm4 0a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5Zm-4 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Zm4 0a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5Z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('rooms.edit', $room->id) }}"
                                                        class="btn btn-sm btn-warning ml-1"><i
                                                            class="fas fa-pencil-alt"></i></a>
                                                    <form id="delete-form-{{ $room->id }}"
                                                        action="{{ route('rooms.destroy', $room->id) }}" class="d-none"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button onclick="deleteForm('{{ $room->id }}')"
                                                        class="btn btn-sm btn-danger ml-1"><i
                                                            class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Tidak ada data</td>
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
        function deleteForm(id) {
            Swal.fire({
                title: 'Hapus data',
                text: "Anda akan menghapus data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#delete-form-${id}`).submit()
                }
            })
        }

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
                            columns: [0, 1]
                        },
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf"></i> Cetak PDF'
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1]
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
        });
    </script>
@endsection
