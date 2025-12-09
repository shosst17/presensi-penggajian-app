@extends('layouts.admin')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Data Kehadiran Saya</h3>
                <div class="card-tools">
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Absen Baru
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Keterlambatan</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $row)
                        <tr>
                            <td>{{ date('d F Y', strtotime($row->date)) }}</td>
                            <td><span class="badge bg-success">{{ $row->check_in_time }}</span></td>
                            <td>
                                @if($row->check_out_time)
                                    <span class="badge bg-danger">{{ $row->check_out_time }}</span>
                                @else
                                    <span class="badge bg-secondary">Belum Pulang</span>
                                @endif
                            </td>
                            <td>
                                @if($row->status == 'present')
                                    <span class="badge bg-primary">Tepat Waktu</span>
                                @elseif($row->status == 'late')
                                    <span class="badge bg-warning text-dark">Terlambat</span>
                                @elseif($row->status == 'overtime_weekend')
                                    <span class="badge bg-info text-dark">Lembur Libur</span> @else
                                    <span class="badge bg-danger">Alpha</span>
                                @endif
                            </td>
                            <td>
                                @if($row->late_minutes > 0)
                                    <span class="text-danger fw-bold">{{ $row->late_minutes }} Menit</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal-foto-{{ $row->id }}">
                                    <i class="bi bi-image"></i> Lihat
                                </button>
                                
                                <div class="modal fade" id="modal-foto-{{ $row->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Bukti Absensi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p class="fw-bold">Foto Masuk:</p>
                                                <img src="{{ asset('storage/attendance_photos/'.$row->check_in_photo) }}" class="img-fluid rounded border mb-3">
                                                
                                                @if($row->check_out_photo)
                                                    <p class="fw-bold border-top pt-2">Foto Pulang:</p>
                                                    <img src="{{ asset('storage/attendance_photos/'.$row->check_out_photo) }}" class="img-fluid rounded border">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection