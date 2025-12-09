@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@if(Auth::user()->role == 'admin')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $data['total_pegawai'] }}</h3>
                <p>Total Pegawai Aktif</p>
            </div>
            <div class="icon"><i class="bi bi-people-fill"></i></div>
            <a href="{{ route('employees.index') }}" class="small-box-footer">Lihat Detail <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $data['pegawai_hadir'] }}</h3>
                <p>Hadir Hari Ini</p>
            </div>
            <div class="icon"><i class="bi bi-person-check-fill"></i></div>
            <a href="{{ route('attendance.recap') }}" class="small-box-footer">Cek Laporan <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $data['total_dept'] }}</h3>
                <p>Departemen</p>
            </div>
            <div class="icon"><i class="bi bi-building"></i></div>
            <a href="{{ route('departments.index') }}" class="small-box-footer">Kelola <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp {{ number_format($data['total_gaji'] / 1000000, 1) }}M</h3>
                <p>Payroll Bulan Ini</p>
            </div>
            <div class="icon"><i class="bi bi-wallet-fill"></i></div>
            <a href="{{ route('payroll.index') }}" class="small-box-footer">Keuangan <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="card card-outline card-dark">
    <div class="card-header">
        <h3 class="card-title">Pantauan Absensi Terkini</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['recent_absences'] as $row)
                <tr>
                    <td>{{ $row->check_in_time }}</td>
                    <td>{{ $row->user->name }}</td>
                    <td>
                        @if($row->status == 'late') <span class="badge bg-danger">Telat {{ $row->late_minutes }}m</span>
                        @elseif($row->status == 'overtime_weekend') <span class="badge bg-info">Lembur Weekend</span>
                        @else <span class="badge bg-success">Tepat Waktu</span> @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@elseif(Auth::user()->role == 'manager')
<div class="alert alert-light border shadow-sm">
    <h4 class="alert-heading">Halo, Manager {{ Auth::user()->name }}!</h4>
    <p>Berikut adalah ringkasan kinerja tim departemen Anda hari ini.</p>
</div>

<div class="row">
    <div class="col-lg-6 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $data['team_hadir'] }} / {{ $data['team_total'] }}</h3>
                <p>Anggota Tim Hadir</p>
            </div>
            <div class="icon"><i class="bi bi-people"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box {{ $data['pending_lembur'] > 0 ? 'bg-danger animate__animated animate__pulse animate__infinite' : 'bg-secondary' }}">
            <div class="inner">
                <h3>{{ $data['pending_lembur'] }}</h3>
                <p>Request Lembur</p>
            </div>
            <div class="icon"><i class="bi bi-clock-history"></i></div>
            <a href="{{ route('overtime.index') }}" class="small-box-footer">Proses <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box {{ $data['pending_cuti'] > 0 ? 'bg-warning' : 'bg-secondary' }}">
            <div class="inner">
                <h3>{{ $data['pending_cuti'] }}</h3>
                <p>Request Cuti</p>
            </div>
            <div class="icon"><i class="bi bi-envelope-paper"></i></div>
            <a href="{{ route('leave.index') }}" class="small-box-footer">Proses <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

@else
<div class="row">
    <div class="col-md-8">
        <div class="card card-outline card-primary shadow">
            <div class="card-body">
                <h3>Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-muted">Jangan lupa absen dan jaga kesehatan ya.</p>
                <a href="{{ route('attendance.create') }}" class="btn btn-lg btn-primary w-100 py-3 shadow-sm">
                    <i class="bi bi-camera-fill me-2"></i> MULAI ABSENSI
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $data['hadir_bulan_ini'] }} Hari</h3>
                <p>Kehadiran Bulan Ini</p>
            </div>
            <div class="icon"><i class="bi bi-calendar-check"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger"><i class="bi bi-alarm"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Terlambat</span>
                <span class="info-box-number">{{ $data['telat_bulan_ini'] }} Menit</span>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="bi bi-lightning-charge"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Lembur Disetujui</span>
                <span class="info-box-number">{{ $data['lembur_acc'] }} Kali</span>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info"><i class="bi bi-cash-coin"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Gaji Terakhir</span>
                <span class="info-box-number">Rp {{ number_format($data['gaji_terakhir']) }}</span>
            </div>
        </div>
    </div>
</div>
@endif

@endsection