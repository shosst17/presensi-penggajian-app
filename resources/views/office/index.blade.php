@extends('layouts.admin')
@section('title', 'Data Lokasi Kantor')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Kantor Cabang</h3>
        <div class="card-tools">
            <a href="{{ route('office.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Kantor
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Nama Kantor</th>
                    <th>Jam Kerja</th>
                    <th>Toleransi (Msk/Plg)</th> <th>Lembur (Min/Max)</th>    <th>Radius</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offices as $office)
                <tr>
                    <td class="fw-bold">{{ $office->name }}</td>
                    <td>
                        <span class="badge bg-success">{{ substr($office->start_time, 0, 5) }}</span> - 
                        <span class="badge bg-danger">{{ substr($office->end_time, 0, 5) }}</span>
                    </td>
                    <td>
                        <small class="d-block text-muted">Msk: {{ $office->entry_grace_minutes }}m</small>
                        <small class="d-block text-muted">Plg: {{ $office->exit_grace_minutes }}m</small>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $office->min_overtime_minutes }}m</span> - 
                        <span class="badge bg-warning text-dark">{{ $office->max_overtime_minutes }}m</span>
                    </td>
                    <td>{{ $office->radius_meters }}m</td>
                    <td>
                        <a href="{{ route('office.edit', $office->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('office.destroy', $office->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kantor ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection