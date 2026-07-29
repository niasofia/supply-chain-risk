<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Global Supply Chain Risk Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .login-card { border-radius: 12px; border: none; }
        .extra-small { font-size: 0.75rem; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-4">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg login-card p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="bi bi-shield-lock-fill text-primary display-4"></i>
                            <h4 class="fw-bold mt-2">Login System</h4>
                            <p class="text-muted small mb-0">Global Supply Chain Risk Intelligence</p>
                        </div>

                        <!-- Alert Jika Login Gagal -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            
                            <!-- Input Email/Username -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email / Username</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                            </div>

                            <!-- Input Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-semibold mb-3">Login</button>
                        </form>

                        <!-- Box Daftar Akun Demo -->
                        <div class="pt-3 border-top">
                            <strong class="small text-muted d-block mb-2 text-center">
                                <i class="bi bi-person-badge me-1 text-primary"></i>Daftar Akun Terdaftar (Klik untuk Isi):
                            </strong>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary text-start d-flex justify-content-between align-items-center" onclick="fillAccount('adminbaru@gmail.com', 'admin12345')">
                                    <div>
                                        <strong class="d-block small">👑 Admin (Administrator)</strong>
                                        <span class="text-muted extra-small">adminbaru@gmail.com | Pass: admin12345</span>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">Pilih Admin</span>
                                </button>

                                <button type="button" class="btn btn-sm btn-outline-secondary text-start d-flex justify-content-between align-items-center" onclick="fillAccount('userbaru@gmail.com', 'user12345')">
                                    <div>
                                        <strong class="d-block small">👤 User (Pengguna Biasa)</strong>
                                        <span class="text-muted extra-small">userbaru@gmail.com | Pass: user12345</span>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill">Pilih User</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillAccount(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>