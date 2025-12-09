@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile text-center">
                <div class="text-center mb-3">
                    @if($user->avatar)
                        <img class="profile-user-img img-fluid img-circle border"
                             src="{{ asset('storage/'.$user->avatar) }}"
                             alt="User profile picture"
                             style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 150px; height: 150px; border-radius: 50%; font-size: 3rem; color: white;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                <p class="text-muted text-center">{{ strtoupper($user->role) }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Departemen</b> <a class="float-end">{{ $user->department->name ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Jabatan</b> <a class="float-end">{{ $user->position->name ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>NIP</b> <a class="float-end">{{ $user->nip ?? '-' }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <h4 class="card-title p-2">Edit Data Diri</h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email tidak dapat diubah.</small>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">No. HP / WA</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Ganti Foto</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" name="avatar">
                            <small class="text-muted">Format: JPG, PNG. Max 2MB.</small>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3 text-danger"><i class="bi bi-lock"></i> Ganti Password</h5>
                    <p class="text-muted small">Kosongkan jika tidak ingin mengganti password.</p>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Password Baru</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Konfirmasi Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-sm-3 col-sm-9">
                            <button type="submit" class="btn btn-danger">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection