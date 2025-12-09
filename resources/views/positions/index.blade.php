@extends('layouts.admin')

@section('title', 'Data Jabatan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">Daftar Jabatan & Posisi</h3>
                <div class="card-tools">
                    <a href="{{ route('positions.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-lg"></i> Tambah Baru
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table datatable table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>Nama Jabatan</th>
                            <th>Departemen</th>
                            <th>Gaji Standar (GP)</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $p->name }}</td>
                            <td>
                                @if($p->department)
                                    <span class="badge bg-info">{{ $p->department->name }}</span>
                                @else
                                    <span class="text-muted fst-italic">Tanpa Dept</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($p->base_salary_default, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('positions.edit', $p->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                <form action="{{ route('positions.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus jabatan ini? Pegawai di jabatan ini mungkin akan error!')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data jabatan. Silakan tambah baru.
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