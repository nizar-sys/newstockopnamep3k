<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pengecekan Item P3K</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-responsive {
            width: 100%;
            margin-bottom: 15px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            background-color: transparent;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Laporan Pengecekan Item P3K</h1>
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
                    <th>Status Verifikasi</th>
                    <th>Tanggal Pengecekan</th>
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
                        <td>{{ $checklist->status }}</td>
                        <td>{{ $checklist->updatedByName }}</td>
                        <td>{{ $checklist->note }}</td>
                        <td>{{ $checklist->status_verif == 'verified' ? 'Terverifikasi' : 'Belum Terverifikasi' }}</td>
                        <td>@date_formatted($checklist->created_at)</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
