<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Global Supply Chain Risk Intelligence</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Leaflet.js CSS (Peta) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { background-color: #f4f6f9; }
        #map { height: 380px; border-radius: 10px; z-index: 1; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-globe2 text-primary me-2"></i>Supply Chain Risk Intelligence</a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white small">Halo, <strong>{{ auth()->user()->name }}</strong> ({{ strtoupper(auth()->user()->role) }})</span>
                
                <!-- Tombol Navigasi Ke Country Monitoring -->
                <a href="{{ route('country.monitoring') }}" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-flag-fill me-1"></i> Country Monitoring
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.risks.index') }}" class="btn btn-sm btn-outline-light">Kelola Risks</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4">
        <h4 class="fw-bold mb-4">Global Intelligence Monitoring Dashboard</h4>

        <!-- Row 1: Peta & Chart -->
        <div class="row mb-4">
            <!-- Global Port Risk Map (Leaflet.js) -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i> Peta Distribusi Risiko Pelabuhan Dunia
                    </div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <!-- Risk Analytics (Chart.js) -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold d-flex align-items-center">
                        <i class="bi bi-graph-up-arrow text-primary me-2"></i> Grafik Tren Indikator Risiko
                    </div>
                    <div class="card-body d-flex align-items-center">
                        <canvas id="riskChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Tabel Indikator Risiko -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold d-flex align-items-center">
                <i class="bi bi-shield-exclamation text-warning me-2"></i> Ringkasan Indikator Risiko Rantai Pasok
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Lokasi / Pelabuhan</th>
                                <th>Kategori</th>
                                <th>Indikator Utama</th>
                                <th>Level Risiko</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($risks as $index => $risk)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $risk->location }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $risk->category }}</span></td>
                                    <td>{{ $risk->indicator }}</td>
                                    <td>
                                        @if($risk->risk_level === 'HIGH')
                                            <span class="badge bg-danger">HIGH</span>
                                        @elseif($risk->risk_level === 'MEDIUM')
                                            <span class="badge bg-warning text-dark">MEDIUM</span>
                                        @else
                                            <span class="badge bg-success">LOW</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data risiko.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Leaflet.js & Chart.js -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // 1. Inisialisasi Peta Leaflet.js
        const map = L.map('map').setView([20, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const ports = @json($ports);

        ports.forEach(port => {
            L.marker([port.lat, port.lng])
                .addTo(map)
                .bindPopup(`<b>${port.name} (${port.country})</b><br>Status: ${port.status}`);
        });

        // 2. Inisialisasi Grafik Chart.js
        const ctx = document.getElementById('riskChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['China', 'Indonesia', 'Germany', 'Netherlands', 'USA'],
                datasets: [{
                    label: 'Skor Risiko Rantai Pasok',
                    data: [78, 45, 62, 25, 50],
                    backgroundColor: [
                        '#dc3545',
                        '#ffc107',
                        '#dc3545',
                        '#198754',
                        '#ffc107'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
</body>
</html>