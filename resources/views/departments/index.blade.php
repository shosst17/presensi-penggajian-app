@extends('layouts.admin')
@section('title', 'Data Departemen')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Departemen</h3>
        <div class="card-tools">
            <a href="{{ route('departments.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah Baru</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table datatable table-striped">
            <thead>
                <tr>
                    <th>Nama Departemen</th>
                    <th>Deskripsi</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                <tr>
                    <td>{{ $d->name }}</td>
                    <td>{{ $d->description ?? '-' }}</td>
                    <td>
                        <a href="{{ route('departments.edit', $d->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('departments.destroy', $d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus departemen ini? Data jabatan & pegawai terkait akan hilang/reset!')">
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