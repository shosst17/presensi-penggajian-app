@extends('layouts.admin')

@section('title', 'Data Penggajian')

@section('content')
<div class="row">
    <div class="col-12">
        @if(Auth::user()->role == 'admin')
        <form action="{{ route('payroll.store') }}" method="POST" class="mb-3">
            @csrf
            <button class="btn btn-success btn-lg shadow" onclick="return confirm('Generate gaji untuk semua pegawai bulan ini?')">
                <i class="bi bi-gear-wide-connected"></i> HITUNG GAJI BULAN INI
            </button>
        </form>
        @endif

        <div class="card card-outline card-success">
            <div class="card-header"><h3 class="card-title">Riwayat Gaji</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Nama Pegawai</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan + Lembur</th>
                            <th>Potongan</th>
                            <th>THP (Bersih)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ $row->month }}</td>
                            <td>{{ $row->user->name ?? 'User Terhapus' }}</td>
                            <td>Rp {{ number_format($row->basic_salary) }}</td>
                            <td class="text-success">+ Rp {{ number_format($row->allowances + $row->overtime_pay) }}</td>
                            <td class="text-danger">- Rp {{ number_format($row->deductions) }}</td>
                            <td class="fw-bold">Rp {{ number_format($row->net_salary) }}</td>
                            <td>
                                <a href="{{ route('payroll.show', $row->id) }}" class="btn btn-info btn-sm text-white">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data gaji.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection