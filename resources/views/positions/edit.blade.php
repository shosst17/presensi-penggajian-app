@extends('layouts.admin')

@section('title', 'Edit Jabatan')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Data Jabatan</h3>
    </div>
    <form action="{{ route('positions.update', $position->id) }}" method="POST">
        @csrf
        @method('PUT') <div class="card-body">
            <div class="form-group mb-3">
                <label>Nama Jabatan</label>
                <input type="text" name="name" class="form-control" value="{{ $position->name }}" required>
            </div>
            
            <div class="form-group mb-3">
                <label>Departemen</label>
                <select name="department_id" class="form-control" required>
                    <option value="">- Pilih -</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ $position->department_id == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Standar Gaji Pokok (Rp)</label>
                <input type="number" name="base_salary_default" class="form-control" value="{{ $position->base_salary_default }}" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Update Data</button>
            <a href="{{ route('positions.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection