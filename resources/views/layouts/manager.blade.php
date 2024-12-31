<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manager Dashboard') - Carbon Footprint</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Default Styles -->
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #198754;
            color: white;
        }

        .sidebar .nav-link {
            font-weight: 500;
            color: white;
            padding: 0.5rem 1rem;
        }

        .sidebar .nav-link.active {
            background-color: #157347; 
            color: white;
        }

        .sidebar .nav-link:hover {
            background-color: #157347;
            color: white;
        }

        .navbar {
            background-color: #f8f9fa;
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
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <a class="navbar-brand mb-0 h1" href="#">Carbon Footprint</a>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i> {{ Auth::guard('manager')->user()->nama_manager }}
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
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('manager/dashboard') ? 'active' : '' }}" href="{{ route('manager.dashboard') }}">
                                <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('manager/faktor-emisi*') ? 'active' : '' }}" 
                               href="{{ route('manager.faktor-emisi.index') }}">
                                <i class="bi bi-calculator me-2"></i>
                                Faktor Emisi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('manager/kompensasi*') ? 'active' : '' }}" 
                               href="{{ route('manager.kompensasi.index') }}">
                                <i class="bi bi-arrow-left-right me-2"></i>
                                Kompensasi Emisi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('manager/carbon-credit*') ? 'active' : '' }}" 
                               href="{{ route('manager.carbon_credit.index') }}">
                                <i class="bi bi-currency-exchange me-2"></i>
                                Lihat Pembelian Carbon Credit
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('manager/comments*') ? 'active' : '' }}" 
                               href="{{ route('manager.comments.index') }}">
                                <i class="bi bi-chat-dots me-2"></i>
                                Komentar Carbon Credit
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