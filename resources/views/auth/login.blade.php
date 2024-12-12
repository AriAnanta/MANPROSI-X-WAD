<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenLedger</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header img {
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
        
        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25,135,84,.25);
        }
        
        .btn-success {
            padding: 12px;
            font-weight: 500;
        }
        
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25,135,84,.25);
        }
        
        .register-links {
            margin-top: 20px;
            padding: 15px;
            border-top: 1px solid #dee2e6;
        }
        
        .register-links p {
            margin-bottom: 10px;
            color: #6c757d;
        }
        
        .register-links .btn {
            margin: 5px;
            width: calc(33.33% - 10px);
            padding: 8px 0;
        }
        
        @media (max-width: 400px) {
            .register-links .btn {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h4>GreenLedger</h4>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="text-white mb-0">Login</h5>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Login Sebagai</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="pengguna">Pengguna</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" 
                               required>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Masuk</button>
                </form>
                
                <div class="register-links text-center">
                    <p>Belum punya akun? Daftar sebagai:</p>
                    <div class="d-flex flex-wrap justify-content-center">
                        <a href="{{ route('register.pengguna') }}" class="btn btn-outline-success">Pengguna</a>
                        <a href="{{ route('register.admin') }}" class="btn btn-outline-success">Admin</a>
                        <a href="{{ route('register.manager') }}" class="btn btn-outline-success">Manager</a>
                    </div>
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