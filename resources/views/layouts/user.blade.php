<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard') - Carbon Footprint</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #198754; /* Warna hijau */
            color: white;
        }

        .sidebar .nav-link {
            font-weight: 500;
            color: white;
            padding: 0.5rem 1rem;
        }

        .sidebar .nav-link.active {
            background-color: #157347; /* Warna hijau lebih gelap */
            color: white;
        }

        .sidebar .nav-link:hover {
            background-color: #157347;
            color: white;
        }

        .navbar {
            background-color: #f8f9fa; /* Warna light */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .navbar-brand {
            font-family: 'Zen Dots', cursive;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                top: 5rem;
            }
        }

        .notification-dropdown {
            padding: 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .notification-item {
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item p {
            margin: 0;
            font-size: 0.9rem;
        }

        .notification-item small {
            font-size: 0.8rem;
        }

        .notification-dropdown {
            padding: 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .notification-item {
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item p {
            margin: 0;
            font-size: 0.9rem;
        }

        .notification-item small {
            font-size: 0.8rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <!-- Tombol Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Nama Brand -->
            <a class="navbar-brand mb-0 h1" href="#">Carbon Footprint User</a>

            <!-- Dropdown Profil -->
            <div class="d-flex align-items-center">
                <!-- Notification Dropdown -->
                <div class="dropdown me-3">
                    <button class="btn btn-light position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        @if($unreadNotifications > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        @forelse($notifications as $notif)
                            <li class="notification-item p-2 border-bottom">
                                <small class="text-muted">{{ date('d/m/Y', strtotime($notif->tanggal)) }}</small>
                                <div class="fw-bold">{{ $notif->kategori_notifikasi }}</div>
                                <p class="mb-1">{{ $notif->deskripsi }}</p>
                                <small class="text-muted">oleh admin [{{ $notif->kode_admin }}]</small>
                            </li>
                        @empty
                            <li class="p-3 text-center">
                                <span class="text-muted">Tidak ada notifikasi</span>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Existing Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> 
                        @if(Auth::guard('pengguna')->check())
                            {{ Auth::guard('pengguna')->user()->nama_user }}
                        @else
                            User
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        @if(Auth::guard('pengguna')->check())
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-3">
                            <a href="{{ url('/dashboard') }}" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                                <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </li>   
                        <li class="nav-item mb-3">
                            <a href="{{ route('emisicarbon.create') }}" class="nav-link {{ Request::is('emisicarbon/create') ? 'active' : '' }}">
                                <i class="bi bi-cloud-upload me-2"></i>
                                Input Emisi Karbon
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a href="{{ route('emisicarbon.index') }}" class="nav-link {{ Request::is('emisicarbon') ? 'active' : '' }}">
                                <i class="bi bi-eye me-2"></i>
                                Lihat Data Emisi Karbon
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 