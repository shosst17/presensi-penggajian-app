@extends('layouts.admin')

@section('title', 'Data Kasbon')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pinjaman</h3>
                <div class="card-tools">
                    @if(Auth::user()->role == 'staff')
                    <a href="{{ route('loan.create') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-cash"></i> Ajukan Kasbon
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table datatable table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Tgl</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Tenor</th>
                            <th>Cicilan/Bln</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ $row->created_at->format('d M Y') }}</td>
                            <td>{{ $row->user->name }}</td>
                            <td>Rp {{ number_format($row->amount) }}</td>
                            <td>{{ $row->installments }} Bulan</td>
                            <td>Rp {{ number_format($row->installment_amount) }}</td>
                            <td class="text-danger fw-bold">Rp {{ number_format($row->remaining_amount) }}</td>
                            <td>
                                @if($row->status == 'pending') <span class="badge bg-secondary">Menunggu</span>
                                @elseif($row->status == 'active') <span class="badge bg-success">Aktif</span>
                                @elseif($row->status == 'paid_off') <span class="badge bg-primary">Lunas</span>
                                @else <span class="badge bg-danger">Ditolak</span> @endif
                            </td>
                            <td>
                                @if(in_array(Auth::user()->role, ['manager', 'director', 'admin']) && $row->status == 'pending')
                                    <form action="{{ route('loan.approve', $row->id) }}" method="POST" class="d-inline">
                                        @csrf <button class="btn btn-success btn-sm" onclick="return confirm('Cairkan dana?')"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form action="{{ route('loan.reject', $row->id) }}" method="POST" class="d-inline">
                                        @csrf <button class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted">Tidak ada data pinjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection