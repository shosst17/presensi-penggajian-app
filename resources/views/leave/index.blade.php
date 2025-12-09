@extends('layouts.admin')

@section('title', 'Data Cuti & Izin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pengajuan</h3>
                <div class="card-tools">
                    @if(Auth::user()->role == 'staff')
                    <a href="{{ route('leave.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Ajukan Cuti
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table datatable table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Tgl Pengajuan</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Tanggal Cuti</th>
                            <th>Alasan</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ $row->created_at->format('d M Y') }}</td>
                            <td>{{ $row->user->name ?? '-' }}</td>
                            <td>
                                @if($row->type == 'sakit') <span class="badge bg-danger">Sakit</span>
                                @elseif($row->type == 'izin') <span class="badge bg-warning text-dark">Izin</span>
                                @else <span class="badge bg-info">Cuti Tahunan</span> @endif
                            </td>
                            <td>{{ date('d/m', strtotime($row->start_date)) }} - {{ date('d/m', strtotime($row->end_date)) }}</td>
                            <td>{{ Str::limit($row->reason, 20) }}</td>
                            <td>
                                @if($row->attachment)
                                    <a href="{{ asset('storage/'.$row->attachment) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                        <i class="bi bi-paperclip"></i> Lihat
                                    </a>
                                @else - @endif
                            </td>
                            <td>
                                @if($row->status == 'pending') <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif(str_contains($row->status, 'approved')) <span class="badge bg-success">Disetujui</span>
                                @else <span class="badge bg-danger">Ditolak</span> @endif
                            </td>
                            <td>
                                @if(Auth::user()->role == 'manager' && $row->status == 'pending')
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('leave.approve', $row->id) }}" method="POST">
                                            @csrf <button class="btn btn-success btn-sm" onclick="return confirm('Setujui?')"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <form action="{{ route('leave.reject', $row->id) }}" method="POST">
                                            @csrf 
                                            <input type="hidden" name="rejection_note" value="Ditolak Manager">
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak?')"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection