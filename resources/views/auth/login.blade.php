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
            background: linear-gradient(to right, #198754, #28a745);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            color: #212529;
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header img {
            max-width: 120px;
        }

        .card-header {
            background-color: #198754;
            border-bottom: none;
            text-align: center;
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
            padding: 15px;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .btn-success {
            background: #198754;
            border: none;
            font-weight: bold;
        }

        .btn-success:hover {
            background: #145d36;
        }

        .register-links {
            margin-top: 20px;
            text-align: center;
        }

        .register-links a {
            display: inline-block;
            margin: 5px;
            padding: 8px 12px;
            color: #198754;
            border: 1px solid #198754;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .register-links a:hover {
            background: #198754;
            color: #fff;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #ccc;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }

            .card-header {
                font-size: 1.2rem;
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
                Login
            </div>
            <div class="card-body">
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

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Masuk</button>
                </form>

                <div class="register-links">
                    <p>Belum punya akun? Daftar sebagai:</p>
                    <a href="{{ route('register.pengguna') }}">Pengguna</a>
                    <a href="{{ route('register.admin') }}">Admin</a>
                    <a href="{{ route('register.manager') }}">Manager</a>
                </div>
            </div>
        </div>

        <div class="footer-text">
            &copy; {{ date('Y') }} GreenLedger. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
