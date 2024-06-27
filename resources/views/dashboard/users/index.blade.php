@extends('layouts.app')
@section('title', 'Data Pengguna')

@push('title-header', 'Data Pengguna')
@push('breadcrumbs')
    <li class="breadcrumb-item active">Data Pengguna</li>
@endpush

@push('action_btn')
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
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
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Avatar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    {{ ucfirst($user->role) }}
                                                </td>
                                                <td>
                                                    <img src="{{ asset('/uploads/images/' . $user->avatar) }}"
                                                        alt="{{ $user->name }}" width="100">
                                                </td>
                                                <td class="d-flex jutify-content-center">
                                                    @if ($user->id != 1)
                                                        <a href="{{ route('users.edit', $user->id) }}"
                                                            class="btn btn-sm btn-warning"><i
                                                                class="fas fa-pencil-alt"></i></a>
                                                        <form id="delete-form-{{ $user->id }}"
                                                            action="{{ route('users.destroy', $user->id) }}" class="d-none"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <button onclick="deleteForm('{{ $user->id }}')"
                                                            class="btn btn-sm btn-danger ml-1"><i
                                                                class="fas fa-trash"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">Tidak ada data</td>
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
                buttons: [
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf"></i>'
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
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
    </script>
@endsection
