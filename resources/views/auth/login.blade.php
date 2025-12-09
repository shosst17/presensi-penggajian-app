<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HRIS</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.11/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta1/dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="login-page bg-body-secondary">
    
    <div class="login-box">
        <div class="card card-outline card-primary shadow-lg">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>SmartGeo</b> HRIS</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silakan masuk untuk memulai sesi</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required autofocus>
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        </div>
                    </div>
                </form>
                
                <p class="mb-1 mt-3">
                    <a href="#" onclick="Swal.fire('Lupa Password?', 'Silakan hubungi Admin HRD untuk reset password.', 'info')">Lupa password?</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta1/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. Jika ada Error Login (Password Salah / Email tidak ada)
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal!',
                text: '{{ $errors->first() }}', // Mengambil pesan error pertama
                confirmButtonColor: '#d33',
                confirmButtonText: 'Coba Lagi'
            });
        @endif

        // 2. Jika ada Pesan Sukses/Gagal dari Session (Misal setelah Logout)
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("status") }}',
                timer: 1500,
                showConfirmButton: false
            });
        @endif
        
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("error") }}'
            });
        @endif
    </script>
</body>
</html>