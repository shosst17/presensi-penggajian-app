<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartGeo HRIS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta1/dist/css/adminlte.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); /* Background Gelap Elegan */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="fw-bold text-dark mb-4">SmartGeo HRIS</h2>
        <p class="text-muted mb-4">Sistem Presensi & Penggajian Terpadu</p>
        
        <div class="d-grid gap-2">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-success btn-lg">Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login Pegawai</a>
                @endauth
            @endif
        </div>
        <div class="mt-4 text-muted small">&copy; 2025 Portofolio Project</div>
    </div>
</body>
</html>