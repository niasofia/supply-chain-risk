<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/dashboard"><i class="bi bi-shield-lock-fill"></i> Admin Panel</a>
            <a href="/dashboard" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</h3>
            <span class="badge bg-primary fs-6">Total: {{ count($users) }} User</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">ADMIN</span>
                                    @else
                                        <span class="badge bg-success">USER</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="text-center">
                                    <form action="/admin/users/{{ $user->id }}/role" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($user->role === 'admin')
                                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Ubah role menjadi User?')">
                                                <i class="bi bi-person-down"></i> Set User
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Jadikan user ini Admin?')">
                                                <i class="bi bi-person-up"></i> Set Admin
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