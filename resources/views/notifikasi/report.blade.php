<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Histori Notifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .meta-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed; /* Kolom memiliki lebar tetap */
            margin: 0 auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f5f5f5;
        }
        /* Kolom Deskripsi diatur agar dapat membungkus teks panjang */
        .long-text {
            word-wrap: break-word;  /* Membungkus kata panjang */
            white-space: pre-wrap; /* Membungkus teks dengan spasi */
        }
        /* Atur lebar spesifik untuk setiap kolom */
        table th:nth-child(1), table td:nth-child(1) { width: 50px; }   /* No */
        table th:nth-child(2), table td:nth-child(2) { width: 150px; }  /* Tujuan */
        table th:nth-child(3), table td:nth-child(3) { width: 100px; }  /* Kategori */
        table th:nth-child(4), table td:nth-child(4) { width: 100px; }  /* Tanggal */
        table th:nth-child(5), table td:nth-child(5) { width: auto; }   /* Deskripsi */
        /* Tambahkan page break setelah setiap 20 baris */
        tr:nth-child(20) {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Histori Notifikasi</h1>
        <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>

    <div class="meta-info">
        <p>Admin: {{ Auth::guard('admin')->user()->nama_admin }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tujuan</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifikasi as $index => $notif)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $notif->pengguna->nama_user ?? $notif->kode_user }}</td>
                    <td>{{ $notif->kategori_notifikasi }}</td>
                    <td>{{ date('d/m/Y', strtotime($notif->tanggal)) }}</td>
                    <td class="long-text">{{ $notif->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Carbon Footprint</p>
    </div>
</body>
</html>
