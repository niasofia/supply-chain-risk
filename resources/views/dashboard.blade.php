<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RISK INTELLIGENCE - Platform Monitoring Rantai Pasok Global</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Tambahan CSS Select2 agar bisa mengetik/mencari nama negara -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        body { background-color: #f0f3f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        #map { height: 350px; border-radius: 8px; z-index: 1; }
        .card { border-radius: 10px; }
        .score-box { font-size: 2.2rem; font-weight: 800; color: #d97706; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: #fff; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 no-print">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-shield-lock-fill text-primary me-2"></i>RISK INTELLIGENCE
                <span class="fw-normal fs-6 text-white-50 ms-2">| Platform Monitoring Rantai Pasok Global</span>
            </a>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <span class="text-white small">Halo, <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong></span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4">

        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between mb-4" id="alertBanner">
            <div>
                <strong id="alertTitle"><i class="bi bi-exclamation-triangle-fill me-2"></i>PERINGATAN (MODERATE RISK) - Global Supply Chain</strong>
                <p class="mb-0 small text-dark" id="alertDesc">Kondisi keamanan Fluktuatif. Disarankan memantau perkembangan situasi secara berkala.</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4 no-print">
            <div class="card-body">
                <h6 class="fw-bold mb-1"><i class="bi bi-sliders me-2 text-primary"></i>Simulasi Input Berita Real-Time</h6>
                <p class="text-muted small mb-3">Ketik atau pilih dari 100+ negara untuk menganalisis sentimen risiko geopolitik secara dinamis.</p>

                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <!-- Dropdown dengan Fitur Cari Negara (Select2) yang sudah diperbarui -->
                        <select class="form-select select2" id="countrySelect" onchange="onCountryChange()">
                            <option value="">-- Cari atau Pilih Negara & Pelabuhan --</option>
                            @isset($countries)
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" 
                                            data-name="{{ $country->name }}" 
                                            data-port="{{ $country->port_name }}" 
                                            data-lat="{{ $country->lat }}" 
                                            data-lng="{{ $country->lng }}"
                                            data-code="{{ strtoupper($country->code ?? '') }}">
                                        {{ strtoupper($country->code ?? '') }} - {{ $country->name }} - Pelabuhan {{ $country->port_name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="newsInput" 
                               value="Rising inflation rates and sudden currency depreciation are causing mild delays in customs clearance and increasing port operation costs.">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100 fw-bold" id="btnAnalyze" onclick="runAnalysis()">
                            Analisis Risiko
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-7 mb-3 mb-lg-0">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
                        <span><i class="bi bi-geo-alt-fill text-primary me-2"></i>Visualisasi Geospatial Pelabuhan & Risiko</span>
                        <span class="badge bg-light text-dark fw-normal border">Gunakan widget kanan atas peta</span>
                    </div>
                    <div class="card-body p-2">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold py-3">
                        Status Risiko: <span id="statusCountryName" class="text-primary">Pilih Negara</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="mb-3">
                                <span class="text-muted small d-block">Berita Teranalisis:</span>
                                <em class="small text-dark bg-light p-2 rounded d-block border" id="displayNews">
                                    "Belum ada analisis berita yang dijalankan."
                                </em>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted small d-block mb-1">Metode Perhitungan:</span>
                                <span class="badge bg-secondary">Weighted Risk Model</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted small d-block">Total Skor Risiko:</span>
                                <div class="d-flex align-items-baseline gap-2">
                                    <span class="score-box" id="displayScore">65%</span>
                                    <span class="badge bg-warning text-dark fs-6" id="displayRiskBadge">Medium Risk (Risiko Sedang)</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded border">
                            <strong class="text-dark small d-block mb-1"><i class="bi bi-lightbulb-fill text-warning me-1"></i>Rekomendasi Sistem:</strong>
                            <p class="small text-muted mb-0" id="displayRecommendation">
                                Lakukan pemantauan berkala pada kondisi pelabuhan dan logistik wilayah terkait.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-5 mb-3 mb-lg-0">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold py-3">
                        Komponen Kontribusi Risiko (Weighted)
                    </div>
                    <div class="card-body">
                        <canvas id="riskChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
                        <span>Detail Indikator Komponen Risiko</span>
                        <button onclick="window.print()" class="btn btn-sm btn-dark no-print">
                            <i class="bi bi-download me-1"></i> Unduh Laporan Risiko (PDF)
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Indikator Risiko</th>
                                        <th>Skor Awal</th>
                                        <th>Bobot</th>
                                        <th>Kontribusi Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="bi bi-cloud-rain me-2 text-primary"></i>Weather Risk</td>
                                        <td>40%</td>
                                        <td>30%</td>
                                        <td class="fw-bold text-primary">12%</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-graph-up-arrow me-2 text-warning"></i>Inflation Risk</td>
                                        <td>50%</td>
                                        <td>20%</td>
                                        <td class="fw-bold text-warning">10%</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-newspaper me-2 text-danger"></i>News Sentiment Risk</td>
                                        <td>100%</td>
                                        <td>40%</td>
                                        <td class="fw-bold text-danger">40%</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-currency-dollar me-2 text-success"></i>Currency Risk</td>
                                        <td>30%</td>
                                        <td>10%</td>
                                        <td class="fw-bold text-success">3%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-clock-history me-2 text-secondary"></i>Riwayat Analisis Terbaru (Log Session)</span>
                <button class="btn btn-sm btn-outline-danger no-print" onclick="clearLogs()">Hapus Riwayat</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="logTable">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu Analisis</th>
                                <th>Negara & Pelabuhan</th>
                                <th>Teks Berita Teranalisis</th>
                                <th>Total Skor</th>
                                <th>Status Risiko</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody">
                            <!-- Log data session dinamis -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Library JS Pendukung -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Aktifkan Select2 dengan tema Bootstrap 5 dan matcher kustom yang fleksibel
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
                    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });
        });

        // Inisialisasi Peta Leaflet
        var map = L.map('map').setView([0.0, 110.0], 4);

        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
        var darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CartoDB' });
        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' });

        L.control.layers({
            "Peta Klasik (Default)": osmLayer,
            "Peta Gelap (Dark Mode)": darkLayer,
            "Citra Satelit": satelliteLayer
        }, null, { position: 'topright' }).addTo(map);

        var currentMarker;

        function updateMapMarker(lat, lng, countryName, portName) {
            if (!lat || !lng) return;
            if (currentMarker) map.removeLayer(currentMarker);

            map.flyTo([lat, lng], 6, { duration: 1.5 });

            currentMarker = L.marker([lat, lng]).addTo(map)
                .bindPopup(`<b>${portName}</b><br>Negara: <b>${countryName}</b><br>Status: <span class="badge bg-warning text-dark">Medium Risk</span>`)
                .openPopup();
        }

        function onCountryChange() {
            var select = document.getElementById('countrySelect');
            var opt = select.options[select.selectedIndex];
            
            if (select.value) {
                var lat = parseFloat(opt.getAttribute('data-lat'));
                var lng = parseFloat(opt.getAttribute('data-lng'));
                var countryName = opt.getAttribute('data-name');
                var portName = opt.getAttribute('data-port');

                document.getElementById('statusCountryName').innerText = countryName + " (" + portName + ")";
                updateMapMarker(lat, lng, countryName, portName);
            }
        }

        // Inisialisasi Chart.js
        const ctx = document.getElementById('riskChart').getContext('2d');
        const riskChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Weather (30%)', 'Inflation (20%)', 'News Sentiment (40%)', 'Currency (10%)'],
                datasets: [{
                    label: 'Kontribusi Persentase Risiko (%)',
                    data: [12, 10, 40, 3],
                    backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444', '#10b981'],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 45 } }
            }
        });

        function runAnalysis() {
            var select = document.getElementById('countrySelect');
            if (!select.value) {
                alert("Silakan cari atau pilih salah satu negara terlebih dahulu!");
                return;
            }

            var opt = select.options[select.selectedIndex];
            var countryName = opt.getAttribute('data-name');
            var portName = opt.getAttribute('data-port');
            var lat = parseFloat(opt.getAttribute('data-lat'));
            var lng = parseFloat(opt.getAttribute('data-lng'));
            
            var btn = document.getElementById('btnAnalyze');
            var newsText = document.getElementById('newsInput').value;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menganalisis...';

            setTimeout(function() {
                btn.disabled = false;
                btn.innerHTML = 'Analisis Risiko';

                document.getElementById('statusCountryName').innerText = countryName + " (" + portName + ")";
                document.getElementById('displayNews').innerText = `"${newsText}"`;
                document.getElementById('alertTitle').innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i>PERINGATAN (MODERATE RISK) - ${countryName} (${portName})`;

                updateMapMarker(lat, lng, countryName, portName);

                var now = new Date();
                var timeStr = now.toTimeString().split(' ')[0];
                var newRow = `
                    <tr>
                        <td>${timeStr}</td>
                        <td><strong>${countryName}</strong><br><small class="text-muted">${portName}</small></td>
                        <td class="small">"${newsText.substring(0, 60)}..."</td>
                        <td><span class="fw-bold">65%</span></td>
                        <td><span class="badge bg-warning text-dark">Medium Risk (Risiko Sedang)</span></td>
                    </tr>
                `;
                document.getElementById('logTableBody').insertAdjacentHTML('afterbegin', newRow);
            }, 800);
        }

        function clearLogs() {
            document.getElementById('logTableBody').innerHTML = '';
        }
    </script>
</body>
</html>