<div class="sidebar bg-success text-white p-4 d-none d-lg-block vh-100">
    <div class="text-center mb-4">
        <h2 style="font-family: 'Zen Dots', cursive; text-shadow: 2px 2px 5px rgba(0,0,0,0.3);">GreenLedger</h2>
    </div>

    <ul class="nav flex-column">
        <!-- Dashboard -->
        <li class="nav-item mb-3">
            <a href="{{ url('/dashboard') }}" class="nav-link text-white d-flex align-items-center py-2 px-3 rounded hover-shadow {{ Request::is('dashboard*') ? 'bg-success-light text-dark' : '' }}">
                <i class="bi bi-house-door me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>   

        <!-- Input Emisi Karbon -->
        <li class="nav-item mb-3">
            <a href="{{ route('emisicarbon.create') }}" class="nav-link text-white d-flex align-items-center py-2 px-3 rounded hover-shadow {{ Request::is('emisicarbon/create') ? 'bg-success-light text-dark' : '' }}">
                <i class="bi bi-cloud-upload me-2"></i>
                <span>Input Emisi Karbon</span>
            </a>
        </li>

        <!-- Lihat Data Emisi Karbon -->
        <li class="nav-item mb-3">
            <a href="{{ route('emisicarbon.index') }}" class="nav-link text-white d-flex align-items-center py-2 px-3 rounded hover-shadow {{ Request::is('emisicarbon') ? 'bg-success-light text-dark' : '' }}">
                <i class="bi bi-eye me-2"></i>
                <span>Lihat Data Emisi Karbon</span>
            </a>
        </li>
    </ul>
</div>
