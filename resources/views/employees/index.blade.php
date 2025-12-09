@extends('layouts.admin')
@section('title', 'Data Pegawai')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Pegawai</h3>
        <div class="card-tools">
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table datatable table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Departemen</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                <tr>
                    <td>{{ $emp->name }}<br><small class="text-muted">{{ $emp->email }}</small></td>
                    <td><span class="badge bg-info">{{ strtoupper($emp->role) }}</span></td>
                    <td>{{ $emp->department->name ?? '-' }}</td>
                    <td>{{ $emp->position->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pegawai ini?')">
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