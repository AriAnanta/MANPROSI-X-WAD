<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
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
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
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
        <h1>{{ $title }}</h1>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <div class="meta-info">
        <p>Admin: {{ $admin }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pembelian</th>
                <th>Tanggal</th>
                <th>Jumlah (kg CO₂)</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carbon_credits as $index => $credit)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $credit->kode_pembelian_carbon_credit }}</td>
                    <td>{{ date('d/m/Y', strtotime($credit->tanggal_pembelian_carbon_credit)) }}</td>
                    <td style="text-align: right">{{ number_format($credit->jumlah_pembelian_carbon_credit, 2) }}</td>
                    <td>{{ $credit->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Pembelian Carbon Credit: {{ number_format($total_pembelian, 2) }} kg CO2</p>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Carbon Footprint</p>
    </div>
</body>
</html> 