<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - HRIS</title>
    
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0d6efd">
    <link rel="apple-touch-icon" href="{{ asset('icons/logo.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.11/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta1/dist/css/adminlte.min.css" />
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @vite(['resources/sass/app.scss']) 
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell"></i>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="badge navbar-badge bg-danger rounded-pill">{{ Auth::user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <span class="dropdown-item dropdown-header">{{ Auth::user()->unreadNotifications->count() }} Notifikasi</span>
                            @forelse(Auth::user()->unreadNotifications as $notif)
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('notification.read', $notif->id) }}" class="dropdown-item text-wrap" style="min-width: 250px;">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                                        <div>
                                            <span class="d-block text-sm fw-bold">{{ Str::limit($notif->data['message'] ?? '-', 40) }}</span>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item text-center text-muted">Tidak ada notifikasi</a>
                            @endforelse
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('notification.readAll') }}" class="dropdown-item dropdown-footer text-center">Tandai Semua Dibaca</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <p>{{ Auth::user()->name }}<small>{{ strtoupper(Auth::user()->role) }}</small></p>
                            </li>
                            <li class="user-footer">
                                <a href="{{ route('profile.index') }}" class="btn btn-default btn-flat">Profil</a>
                                <a href="{{ url('/keluar') }}" class="btn btn-default btn-flat float-end">Sign out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ url('/home') }}" class="brand-link">
                    <span class="brand-text fw-light"><b>SmartGeo</b> HRIS</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-item"><a href="{{ route('home') }}" class="nav-link"><i class="bi bi-speedometer"></i><p>Dashboard</p></a></li>
                        
                        @if(Auth::user()->role == 'staff')
                            <li class="nav-item"><a href="{{ route('attendance.index') }}" class="nav-link"><i class="bi bi-geo-alt"></i><p>Riwayat Absensi</p></a></li>
                            <li class="nav-item"><a href="{{ route('overtime.index') }}" class="nav-link"><i class="bi bi-clock-history"></i><p>Lembur</p></a></li>
                            <li class="nav-item"><a href="{{ route('leave.index') }}" class="nav-link"><i class="bi bi-calendar-check"></i><p>Izin & Cuti</p></a></li>
                            <li class="nav-item"><a href="{{ route('loan.index') }}" class="nav-link"><i class="bi bi-wallet2"></i><p>Kasbon</p></a></li>
                            <li class="nav-item"><a href="{{ route('payroll.index') }}" class="nav-link"><i class="bi bi-receipt"></i><p>Slip Gaji</p></a></li>
                        @endif

                        @if(in_array(Auth::user()->role, ['admin', 'manager', 'director']))
                            <li class="nav-header">MANAJEMEN</li>
                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item"><a href="{{ route('office.index') }}" class="nav-link"><i class="bi bi-building-gear"></i><p>Lokasi Kantor</p></a></li>
                                <li class="nav-item"><a href="{{ route('departments.index') }}" class="nav-link"><i class="bi bi-diagram-3"></i><p>Departemen</p></a></li>
                                <li class="nav-item"><a href="{{ route('positions.index') }}" class="nav-link"><i class="bi bi-briefcase"></i><p>Jabatan</p></a></li>
                            @endif
                            <li class="nav-item"><a href="{{ route('employees.index') }}" class="nav-link"><i class="bi bi-people"></i><p>Data Pegawai</p></a></li>
                            <li class="nav-item"><a href="{{ route('overtime.index') }}" class="nav-link"><i class="bi bi-check2-circle"></i><p>Approval Lembur</p></a></li>
                            <li class="nav-item"><a href="{{ route('leave.index') }}" class="nav-link"><i class="bi bi-file-earmark-check"></i><p>Approval Cuti</p></a></li>
                            <li class="nav-item"><a href="{{ route('loan.index') }}" class="nav-link"><i class="bi bi-cash-stack"></i><p>Approval Kasbon</p></a></li>
                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item"><a href="{{ route('payroll.index') }}" class="nav-link"><i class="bi bi-calculator"></i><p>Penggajian</p></a></li>
                                <li class="nav-item"><a href="{{ route('attendance.recap') }}" class="nav-link"><i class="bi bi-printer"></i><p>Laporan Absensi</p></a></li>
                            @endif
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6"><h3 class="mb-0">@yield('title')</h3></div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>
        
        <footer class="app-footer"><strong>Copyright &copy; 2025 SmartGeo HRIS.</strong></footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta1/dist/js/adminlte.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // DataTables Config
            $('.table').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json" },
                "responsive": true,
                "autoWidth": false
            });

            // SweetAlert Session
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" });
            @endif
            @if(session('warning'))
                Swal.fire({ icon: 'warning', title: 'Perhatian!', text: "{{ session('warning') }}" });
            @endif

            // === PWA REGISTRATION (BARU) ===
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('PWA Service Worker Registered with scope:', registration.scope);
                }).catch(function(err) {
                    console.log('PWA Service Worker registration failed:', err);
                });
            }
        });
    </script>
</body>
</html>