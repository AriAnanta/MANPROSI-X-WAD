<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Carbon Footprint</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
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
    </style>
    @stack('styles')
</head>
<body>
       <!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="font-family: 'Roboto', sans-serif; color: black;">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="#" style="font-weight: bold; font-size: 1.5rem; color: black;">
            <i class="fas fa-leaf me-2"></i> Carbon Footprint
        </a>

        <!-- Toggler Button for Mobile View -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">
                <!-- Notifications Dropdown -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: black;">
                        <i class="fas fa-bell"></i>
                        @if($unreadAdminNotifications > 0)
                            <span class="badge bg-warning position-absolute top-0 start-100 translate-middle">{{ $unreadAdminNotifications }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="max-height: 300px; overflow-y: auto;">
                        <h6 class="dropdown-header text-success">Notifikasi Terbaru</h6>
                        @forelse($adminNotifications as $notification)
                            <li>
                                <a class="dropdown-item" href="#" style="color: black;">
                                    <small class="text-muted d-block">{{ \Carbon\Carbon::parse($notification['created_at'])->format('d M Y H:i') }}</small>
                                    {{ $notification['message'] }}
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item" style="color: black;">Tidak ada notifikasi terbaru</span></li>
                        @endforelse
                    </ul>
                </li>

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Auth::guard('admin')->user()->nama_admin }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>



    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/emisicarbon*') ? 'active' : '' }}" href="{{ route('admin.emissions.index') }}">
                                <i class="bi bi-cloud-upload me-2"></i> 
                                Kelola Emisi Karbon
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-person-lines-fill me-2"></i>  
                                Kelola Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/carbon_credit*') ? 'active' : '' }}" 
                               href="{{ route('carbon_credit.index') }}">
                                <i class="bi bi-cart me-2"></i>
                                Pembelian Carbon Credit
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}" href="{{ route('notifikasi.create') }}">
                                <i class="bi bi-bell me-2"></i> 
                                Buat Notifikasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/emisicarbon/list-report') ? 'active' : '' }}" 
                               href="{{ route('admin.emissions.list_report') }}">
                                <i class="bi bi-printer me-2"></i> 
                                Cetak Laporan Emisi Karbon
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/carbon_credit/list-report') ? 'active' : '' }}" 
                               href="{{ route('carbon_credit.list_report') }}">
                                <i class="bi bi-printer me-2"></i> 
                                Cetak Laporan Pembelian Carbon Credit
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/comments*') ? 'active' : '' }}" 
                               href="{{ route('admin.comments.index') }}">
                                <i class="bi bi-chat-dots me-2"></i>
                                Lihat Komentar
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

    @push('scripts')
    <script>
    function markAsRead(notificationId, url) {
        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.href = url;
        });
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.reload();
        });
    }
    </script>
    @endpush

    @push('scripts')
    <script>
    function markAsRead(notificationId, url) {
        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.href = url;
        });
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.reload();
        });
    }
    </script>
    @endpush
</body>
</html> 