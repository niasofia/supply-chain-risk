<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Supply Chain Risk Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="card login-card p-4 bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-info">RISK INTELLIGENCE</h3>
            <p class="text-muted small">Silakan login untuk mengakses dashboard pemantauan</p>
        </div>

        @if($errors->has('loginError'))
            <div class="alert alert-danger border-0 py-2 small mb-3">
                {{ $errors->first('loginError') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label small fw-bold text-secondary">Alamat Email</label>
                <input type="email" name="email" class="form-control" id="email" 
                       placeholder="admin@gmail.com" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label small fw-bold text-secondary">Password</label>
                <input type="password" name="password" class="form-control" id="password" 
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                Masuk ke Dashboard
            </button>
        </form>
    </div>

</body>
</html>