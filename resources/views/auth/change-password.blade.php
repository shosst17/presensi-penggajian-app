@extends('layouts.app') 
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="bi bi-shield-lock"></i> Keamanan Akun</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        Halo <strong>{{ Auth::user()->name }}</strong>,<br>
                        Anda masih menggunakan password default. Demi keamanan, silakan ganti password Anda sekarang.
                    </div>
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Password Lama (Default)</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Ulangi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Ganti Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection