@extends('layouts.admin')

@section('title', 'Data Lembur')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pengajuan Lembur</h3>
                <div class="card-tools">
                    @if(Auth::user()->role == 'staff')
                    <a href="{{ route('overtime.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Ajukan Lembur
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table datatable table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pegawai</th>
                            <th>Jam</th>
                            <th>Durasi</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ date('d M Y', strtotime($row->date)) }}</td>
                            <td>{{ $row->user->name ?? '-' }}</td>
                            <td>{{ substr($row->start_time, 0, 5) }} - {{ substr($row->end_time, 0, 5) }}</td>
                            <td>{{ $row->duration_minutes }} Menit</td>
                            <td>{{ Str::limit($row->reason, 20) }}</td>
                            <td>
                                @if($row->status == 'pending') <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($row->status == 'approved') <span class="badge bg-success">Disetujui</span>
                                @else <span class="badge bg-danger">Ditolak</span> @endif
                            </td>
                            <td>
                                @if(Auth::user()->role == 'manager' && $row->status == 'pending')
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('overtime.approve', $row->id) }}" method="POST">
                                            @csrf <button class="btn btn-success btn-sm" onclick="return confirm('Setujui?')"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <form action="{{ route('overtime.reject', $row->id) }}" method="POST">
                                            @csrf 
                                            <input type="hidden" name="rejection_note" value="Ditolak Manager">
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak pengajuan ini?')"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data lembur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection