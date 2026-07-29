<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Global Supply Chain Risk Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .login-card { border-radius: 12px; border: none; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-4">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg login-card p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus-fill text-primary display-4"></i>
                            <h4 class="fw-bold mt-2">Daftar Akun Baru</h4>
                            <p class="text-muted small mb-0">Global Supply Chain Risk Intelligence</p>
                        </div>

                        <!-- Alert Error Validasi -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf
                            
                            <!-- Input Nama Lengkap -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Nama Anda" required autofocus>
                            </div>

                            <!-- Input Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required>
                            </div>

                            <!-- Input Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 Karakter" required>
                            </div>

                            <!-- Input Konfirmasi Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi Password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-semibold mb-3">Daftar Sekarang</button>

                            <div class="text-center pt-3 border-top">
                                <span class="small text-muted">Sudah punya akun? </span>
                                <a href="{{ route('login') }}" class="small fw-bold text-primary text-decoration-none">Login di sini</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
