<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>
    <h2>Rekap Transaksi</h2>
    <table>
    <thead>
        <tr>
            <th>Nama Device</th>
            <th>Total Transaksi</th>
            <th>Total Setor</th>
            <th>Total Pengeluaran</th>
            <th>Sisa</th>
            <th>% Sisa</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekap as $item)
            <tr>
                <td>{{ $item['nama'] }}</td>
                <td>Rp {{ number_format($item['total_transaksi'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item['total_setor'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item['total_pengeluaran'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item['sisa'], 0, ',', '.') }}</td>
                <td>{{ $item['persentase'] }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
