@extends('layouts.admin')
@section('title', 'Tambah Departemen')
@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Form Departemen</h3></div>
    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group mb-3">
                <label>Nama Departemen</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Information Technology" required>
            </div>
            <div class="form-group mb-3">
                <label>Deskripsi (Opsional)</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection