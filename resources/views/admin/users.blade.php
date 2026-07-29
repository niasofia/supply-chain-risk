<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Admin Control Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .admin-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark admin-header px-4 py-3 shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-warning" href="{{ route('dashboard') }}">
                <i class="bi bi-crown-fill me-2"></i>ADMIN CONTROL CENTER
                <span class="text-white-50 fs-6 fw-normal ms-2">| Manajemen Pengguna</span>
            </a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <a href="{{ route('admin.risks.index') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-shield-slash-fill me-1"></i> Kelola Risk Dataset
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-speedometer2 me-1"></i> Ke Dashboard Utama
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-4">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Pengguna Terdaftar</h4>
                    <p class="text-muted small mb-0">Kelola peranan hak akses (Role Admin & User) pada platform Risk Intelligence.</p>
                </div>
                <span class="badge bg-primary fs-6 px-3 py-2">Total: {{ count($users) }} Pengguna</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role Saat Ini</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-center">Aksi Manajemen Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold"><i class="bi bi-crown-fill me-1"></i>ADMIN</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2"><i class="bi bi-person-fill me-1"></i>USER</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($user->role === 'admin')
                                            <input type="hidden" name="role" value="user">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Ubah role {{ $user->name }} menjadi User biasa?')">
                                                <i class="bi bi-person-down me-1"></i> Ubah ke User Biasa
                                            </button>
                                        @else
                                            <input type="hidden" name="role" value="admin">
                                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Jadikan {{ $user->name }} sebagai Admin?')">
                                                <i class="bi bi-crown me-1"></i> Jadikan Admin
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
