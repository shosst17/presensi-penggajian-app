@extends('layouts.admin')

@section('title', 'Laporan Rekap Absensi')

@section('content')
<div class="row">
    <div class="col-12">
        
        <div class="card card-outline card-primary mb-3">
            <div class="card-header">
                <h3 class="card-title">Filter Laporan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('attendance.recap') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select name="month" class="form-control">
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select name="year" class="form-control">
                                    @for($y=date('Y'); $y>=2023; $y--)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-filter"></i> Tampilkan
                            </button>
                            <button type="submit" name="download_pdf" value="1" class="btn btn-danger w-100">
                                <i class="bi bi-file-pdf"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">Hasil Rekapitulasi</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table datatable table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pegawai</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($row->date)) }}</td>
                            <td>{{ $row->user->name }}</td>
                            <td>{{ $row->check_in_time }}</td>
                            <td>{{ $row->check_out_time ?? '-' }}</td>
                            <td>
                                @if($row->status == 'late')
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @endif
                            </td>
                            <td>
                                @if($row->late_minutes > 0)
                                    <span class="text-danger fw-bold">{{ $row->late_minutes }} Menit</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada data absensi pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection