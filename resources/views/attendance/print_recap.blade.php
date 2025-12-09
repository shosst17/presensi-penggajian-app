<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN REKAPITULASI ABSENSI</h2>
        <p>Periode: {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }}</p>
        <p>{{ config('app.name') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 25%">Nama Pegawai</th>
                <th style="width: 15%">Masuk</th>
                <th style="width: 15%">Pulang</th>
                <th style="width: 15%">Status</th>
                <th style="width: 10%">Telat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $row)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
                <td>{{ $row->user->name }}</td>
                <td>{{ $row->check_in_time }}</td>
                <td>{{ $row->check_out_time ?? '-' }}</td>
                <td>{{ $row->status == 'late' ? 'Terlambat' : 'Tepat Waktu' }}</td>
                <td>{{ $row->late_minutes > 0 ? $row->late_minutes.' m' : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
        <br><br><br>
        <p><strong>( {{ Auth::user()->name }} )</strong></p>
    </div>
</body>
</html>