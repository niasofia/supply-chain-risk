<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Risk - Admin Panel</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 10px; }
        .table th { font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-shield-lock-fill text-info me-1"></i> Admin Panel
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <div class="container my-5">
        
        <!-- ALERT NOTIFIKASI SUKSES -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ALERT NOTIFIKASI ERROR (VALIDASI) -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Mohon periksa kembali inputan Anda:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <!-- HEADER HALAMAN -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Kelola Data Risk
                </h3>
                <p class="text-muted small mb-0">Manajemen indikator, tingkat ancaman, dan ambang batas risiko pasokan pelabuhan.</p>
            </div>
            <!-- Tombol Pemicu Modal Tambah -->
            <button class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahRisk">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data Risiko
            </button>
        </div>

        <!-- CARDS RINGKASAN STATISTIK -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body d-flex align-items-center justify-content-between p-3">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">Risiko Tinggi (High)</h6>
                            <h3 class="fw-bold mb-0">{{ isset($risks) ? $risks->where('risk_level', 'HIGH')->count() : 0 }} Pelabuhan</h3>
                        </div>
                        <i class="bi bi-shield-slash fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-dark">
                    <div class="card-body d-flex align-items-center justify-content-between p-3">
                        <div>
                            <h6 class="text-black-50 text-uppercase mb-1 small fw-bold">Risiko Sedang (Medium)</h6>
                            <h3 class="fw-bold mb-0">{{ isset($risks) ? $risks->where('risk_level', 'MEDIUM')->count() : 0 }} Pelabuhan</h3>
                        </div>
                        <i class="bi bi-exclamation-diamond fs-1 text-black-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body d-flex align-items-center justify-content-between p-3">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-1 small fw-bold">Risiko Rendah (Low)</h6>
                            <h3 class="fw-bold mb-0">{{ isset($risks) ? $risks->where('risk_level', 'LOW')->count() : 0 }} Pelabuhan</h3>
                        </div>
                        <i class="bi bi-shield-check fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL DATA RISIKO -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-table me-2"></i>Daftar Indikator Risiko Pelabuhan</h6>
                <span class="badge bg-secondary">Total: {{ isset($risks) ? $risks->count() : 0 }} Indikator</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>Lokasi / Pelabuhan</th>
                                <th>Kategori Risiko</th>
                                <th>Indikator Utama</th>
                                <th class="text-center">Tingkat Risiko</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($risks ?? [] as $index => $risk)
                                <tr>
                                    <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $risk->location }}</td>
                                    <td>{{ $risk->category }}</td>
                                    <td>{{ $risk->indicator }}</td>
                                    <td class="text-center">
                                        @if ($risk->risk_level == 'HIGH')
                                            <span class="badge bg-danger px-3 py-2"><i class="bi bi-exclamation-octagon me-1"></i> HIGH</span>
                                        @elseif ($risk->risk_level == 'MEDIUM')
                                            <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-exclamation-triangle me-1"></i> MEDIUM</span>
                                        @else
                                            <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i> LOW</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditRisk{{ $risk->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        
                                        <!-- Tombol Hapus -->
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapusRisk{{ $risk->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL EDIT DATA (DINAMIS UNTUK TIAP ROW) -->
                                <div class="modal fade" id="modalEditRisk{{ $risk->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-pencil-square text-warning me-2"></i>Edit Indikator Risiko
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.risks.update', $risk->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Lokasi / Pelabuhan</label>
                                                        <input type="text" name="location" class="form-control" value="{{ $risk->location }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Kategori Risiko</label>
                                                        <select name="category" class="form-select" required>
                                                            <option value="Kongesti Logistik" {{ $risk->category == 'Kongesti Logistik' ? 'selected' : '' }}>Kongesti Logistik</option>
                                                            <option value="Cuaca Ekstrem" {{ $risk->category == 'Cuaca Ekstrem' ? 'selected' : '' }}>Cuaca Ekstrem</option>
                                                            <option value="Operasional Alat" {{ $risk->category == 'Operasional Alat' ? 'selected' : '' }}>Operasional Alat</option>
                                                            <option value="Keamanan" {{ $risk->category == 'Keamanan' ? 'selected' : '' }}>Keamanan / Geopolitik</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Indikator Utama</label>
                                                        <input type="text" name="indicator" class="form-control" value="{{ $risk->indicator }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Tingkat Risiko</label>
                                                        <select name="risk_level" class="form-select" required>
                                                            <option value="LOW" {{ $risk->risk_level == 'LOW' ? 'selected' : '' }}>LOW (Rendah)</option>
                                                            <option value="MEDIUM" {{ $risk->risk_level == 'MEDIUM' ? 'selected' : '' }}>MEDIUM (Sedang)</option>
                                                            <option value="HIGH" {{ $risk->risk_level == 'HIGH' ? 'selected' : '' }}>HIGH (Tinggi)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning fw-semibold"><i class="bi bi-save me-1"></i> Update Data</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL KONFIRMASI HAPUS -->
                                <div class="modal fade" id="modalHapusRisk{{ $risk->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content text-center p-3">
                                            <div class="modal-body">
                                                <i class="bi bi-exclamation-circle text-danger display-3 mb-3 d-block"></i>
                                                <h5 class="fw-bold mb-2">Hapus Data?</h5>
                                                <p class="text-muted small mb-3">Apakah kamu yakin ingin menghapus data risiko <strong>{{ $risk->location }}</strong>?</p>
                                                <form action="{{ route('admin.risks.destroy', $risk->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" class="btn btn-light border px-3" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger px-3">Ya, Hapus</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        Belum ada data risiko. Silakan tambahkan data baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL TAMBAH DATA RISIKO -->
    <div class="modal fade" id="modalTambahRisk" tabindex="-1" aria-labelledby="modalTambahRiskLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="modalTambahRiskLabel">
                        <i class="bi bi-plus-circle text-info me-2"></i>Tambah Indikator Risiko
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('admin.risks.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lokasi / Pelabuhan</label>
                            <input type="text" name="location" class="form-control" placeholder="Contoh: Pelabuhan Belawan" value="{{ old('location') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori Risiko</label>
                            <select name="category" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Kongesti Logistik" {{ old('category') == 'Kongesti Logistik' ? 'selected' : '' }}>Kongesti Logistik</option>
                                <option value="Cuaca Ekstrem" {{ old('category') == 'Cuaca Ekstrem' ? 'selected' : '' }}>Cuaca Ekstrem</option>
                                <option value="Operasional Alat" {{ old('category') == 'Operasional Alat' ? 'selected' : '' }}>Operasional Alat</option>
                                <option value="Keamanan" {{ old('category') == 'Keamanan' ? 'selected' : '' }}>Keamanan / Geopolitik</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Indikator Utama</label>
                            <input type="text" name="indicator" class="form-control" placeholder="Contoh: Waktu Antre Kapal > 24 Jam" value="{{ old('indicator') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tingkat Risiko</label>
                            <select name="risk_level" class="form-select" required>
                                <option value="LOW" {{ old('risk_level') == 'LOW' ? 'selected' : '' }}>LOW (Rendah)</option>
                                <option value="MEDIUM" {{ old('risk_level') == 'MEDIUM' ? 'selected' : '' }}>MEDIUM (Sedang)</option>
                                <option value="HIGH" {{ old('risk_level') == 'HIGH' ? 'selected' : '' }}>HIGH (Tinggi)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-semibold"><i class="bi bi-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>