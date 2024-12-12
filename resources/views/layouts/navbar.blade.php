<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <!-- Tombol Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Nama Brand -->
        <a class="navbar-brand mb-0 h1" href="#" style="font-family: 'Zen Dots', cursive;"></a>

        <!-- Dropdown Profil -->
        <div class="d-flex align-items-center">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar Menu untuk Layar Kecil -->
<div class="collapse" id="sidebarMenu">
    <div class="bg-success text-white p-4">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="bi bi-house-door"></i> Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="bi bi-gear"></i> Settings</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-white"><i class="bi bi-cloud-upload"></i> Input Emisi Karbon</a>
            </li>
        </ul>
    </div>
</div>
