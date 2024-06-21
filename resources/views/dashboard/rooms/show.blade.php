@extends('layouts.app')
@section('title', 'Detil Data Ruangan')

@push('title-header', $room->name)
@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Data Ruangan</a></li>
    <li class="breadcrumb-item active">Detil Data Ruangan</li>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('rooms.item.update', $room->id) }}" method="POST" role="form"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="name">Nama Ruangan</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" placeholder="Nama Ruangan"
                                                value="{{ old('name', $room->name) }}" name="name" disabled readonly>

                                            @error('name')
                                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="name">Daftar Isi Kotak P3K</label>
                                            <div class="table-responsive">
                                                <table class="table table-borderless" id="dynamic_field">
                                                    <thead>
                                                        <tr style="background: #DDF5FF;">
                                                            <th>Nama Barang</th>
                                                            <th>Jumlah Standar</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($room->items as $item)
                                                            <tr class="item-row">
                                                                <td><input type="text" name="item_name[]"
                                                                        class="form-control" required
                                                                        value="{{ $item->name }}"></td>
                                                                <td><input type="number" name="item_standard_qty[]"
                                                                        class="form-control" required
                                                                        value="{{ $item->standard_qty }}"></td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm item-remove">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="item-row">
                                                                <td><input type="text" name="item_name[]"
                                                                        class="form-control" required></td>
                                                                <td><input type="number" name="item_standard_qty[]"
                                                                        class="form-control" required></td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm item-remove">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-left">
                                                                <button type="button"
                                                                    class="btn btn-outline-primary btn-md" id="add">
                                                                    Tambah daftar isi
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                        <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var addButton = document.getElementById('add');
            var tableBody = document.querySelector('#dynamic_field tbody');

            addButton.addEventListener('click', function() {
                var newRow = document.createElement('tr');
                newRow.classList.add('item-row');
                newRow.innerHTML = `
                <td><input type="text" name="item_name[]" class="form-control" required></td>
                <td><input type="number" name="item_standard_qty[]" class="form-control" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm item-remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
                tableBody.appendChild(newRow);

                newRow.querySelector('.item-remove').addEventListener('click', function() {
                    newRow.remove();
                });
            });

            document.querySelectorAll('.item-remove').forEach(function(button) {
                button.addEventListener('click', function() {
                    button.closest('tr').remove();
                });
            });
        });
    </script>
@endsection
