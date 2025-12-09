<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        .text-right { text-align: right; }
        .bg-gray { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SLIP GAJI</h2>
        <p>{{ config('app.name') }} - Periode: {{ $payroll->month }}</p>
    </div>

    <p><strong>Nama:</strong> {{ $payroll->user->name }}</p>
    <p><strong>Jabatan:</strong> {{ $payroll->user->position->name ?? '-' }}</p>

    <table>
        <tr class="bg-gray">
            <th>KETERANGAN</th>
            <th class="text-right">JUMLAH (IDR)</th>
        </tr>
        <tr>
            <td>Gaji Pokok</td>
            <td class="text-right">{{ number_format($payroll->basic_salary) }}</td>
        </tr>
        <tr>
            <td>Tunjangan & Transport</td>
            <td class="text-right">{{ number_format($payroll->allowances) }}</td>
        </tr>
        <tr>
            <td>Upah Lembur</td>
            <td class="text-right">{{ number_format($payroll->overtime_pay) }}</td>
        </tr>
        <tr>
            <td style="color: red;">Potongan (Kasbon/Telat)</td>
            <td class="text-right" style="color: red;">- {{ number_format($payroll->deductions) }}</td>
        </tr>
        <tr class="bg-gray">
            <td><strong>TAKE HOME PAY</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($payroll->net_salary) }}</strong></td>
        </tr>
    </table>
    
    <br><br>
    <div style="text-align: right;">
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>
</body>
</html>