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
            background: linear-gradient(to right, #198754, #28a745);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .register-container {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            color: #212529;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .register-header {
            text-align: center;
            padding: 20px;
            background-color: #198754;
            color: #fff;
        }

        .register-header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .card {
            border: none;
        }

        .card-body {
            padding: 30px;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .btn-success {
            background-color: #198754;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background-color: #145e38;
        }

        .text-success {
            font-weight: bold;
        }

        .text-success:hover {
            text-decoration: underline;
        }

        .text-center p {
            margin-top: 15px;
        }

        small {
            color: #6c757d;
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 10px;
            }

            .card-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>Register Admin</h2>
        </div>

        <div class="card">
            <div class="card-body">
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
            <small>&copy; {{ date('Y') }} GreenLedger. All rights reserved.</small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
