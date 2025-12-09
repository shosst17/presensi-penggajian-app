@extends('layouts.admin')
@section('title', 'Tambah Jabatan')
@section('content')
<div class="card card-success">
    <div class="card-header"><h3 class="card-title">Form Jabatan</h3></div>
    <form action="{{ route('positions.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group mb-3">
                <label>Nama Jabatan</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Senior Programmer" required>
            </div>
            <div class="form-group mb-3">
                <label>Departemen</label>
                <select name="department_id" class="form-control" required>
                    <option value="">- Pilih Departemen -</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label>Standar Gaji Pokok (Rp)</label>
                <input type="number" name="base_salary_default" class="form-control" placeholder="Contoh: 8000000" required>
                <small class="text-muted">Nilai ini akan otomatis muncul saat mendaftarkan pegawai baru.</small>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('positions.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection