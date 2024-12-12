<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - GreenLedger</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .register-container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
            margin: auto;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-header img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: #198754;
            border-bottom: none;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0 !important;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h4>GreenLedger</h4>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="text-white mb-0">Register Admin</h5>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.admin') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nama_admin" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama_admin') is-invalid @enderror" 
                               name="nama_admin" value="{{ old('nama_admin') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_telepon" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                               name="no_telepon" value="{{ old('no_telepon') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" 
                               name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Register</button>
                </form>

                <div class="text-center mt-3">
                    <p>Sudah punya akun? <a href="{{ route('login') }}" class="text-success">Login di sini</a></p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <small class="text-muted">&copy; {{ date('Y') }} GreenLedger. All rights reserved.</small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 