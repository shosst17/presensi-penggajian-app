@extends('layouts.admin')

@section('title', 'Edit Departemen')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit Departemen</h3>
            </div>
            <form action="{{ route('departments.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Nama Departemen</label>
                        <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ $department->description }}</textarea>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection