<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Global Supply Chain Risk Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { background-color: #f4f6f9; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        #map { height: 380px; border-radius: 12px; }

        /* STYLE UNTUK EFEK LOADING SHIMMER */
        .shimmer-bg {
            background: linear-gradient(90deg, #eff1f3 4%, #e2e5e7 25%, #eff1f3 36%);
            background-size: 200% 100%;
            animation: shimmerAnimation 1.5s infinite linear;
            border-radius: 4px;
            display: inline-block;
            color: transparent !important;
        }
        @keyframes shimmerAnimation {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-text-long { width: 100%; height: 16px; }
        .shimmer-text-short { width: 60%; height: 24px; }
        .shimmer-score { width: 120px; height: 60px; }

        /* STYLE UNTUK ANIMASI BERKEDIP (PULSING) MARKER PETA */
        .custom-pulsing-marker {
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.7);
            animation: pulse-animation 1.5s infinite;
        }
        @keyframes pulse-animation {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(var(--pulse-color), 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 15px rgba(var(--pulse-color), 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(var(--pulse-color), 0); }
        }

        /* ANIMASI BERKEDIP UNTUK BANNER WARNING HIGH RISK */
        @keyframes alert-pulse {
            0% { opacity: 1; }
            50% { opacity: 0.85; }
            100% { opacity: 1; }
        }
        .pulse-alert {
            animation: alert-pulse 2s infinite ease-in-out;
        }

        /* STYLE CETAK PDF */
        @media print {
            body { background-color: #fff; color: #000; font-size: 12pt; }
            .navbar, #btnAnalyze, .input-group, #map, .btn-export-area, p.text-muted, .no-print, #earlyWarningBanner, .admin-banner { display: none !important; }
            .card { box-shadow: none; border: 1px solid #ddd; margin-bottom: 20px; page-break-inside: avoid; }
            .container-fluid { padding: 0 !important; }
            #riskChart { max-height: 200px !important; }
            body::before {
                content: "LAPORAN RESMI ANALISIS RISIKO RANTAI PASOK GLOBAL\nDicetak pada: " attr(data-print-date) "\n----------------------------------------------------------------------";
                white-space: pre-wrap; font-weight: bold; text-align: center; display: block; margin-bottom: 30px; font-size: 14pt;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <a class="navbar-brand fw-bold text-info" href="#">RISK INTELLIGENCE</a>
                <span class="navbar-text text-white d-none d-md-inline">| Platform Monitoring Rantai Pasok Global</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Status User / Role Indicator -->
                <span class="text-light small d-none d-sm-inline">
                    Logged in as: <strong>{{ auth()->user()->name ?? auth()->user()->email }}</strong>
                    @if(auth()->user()->role === 'admin')
                        <span class="badge bg-danger ms-1">ADMIN</span>
                    @else
                        <span class="badge bg-success ms-1">USER</span>
                    @endif
                </span>

                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm fw-bold px-3">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">

        <!-- 👑 PANEL CONTROL KHUSUS ADMIN (Hanya Tampil Jika Login Sebagai Admin) -->
        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="row mb-4 admin-banner">
            <div class="col-12">
                <div class="card bg-primary text-white p-3 shadow-sm border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold mb-1">👑 Panel Mode Administrator</h6>
                            <p class="small mb-0 opacity-75">Anda memiliki hak akses penuh untuk mengelola pengguna, data pelabuhan, dan sistem.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-warning btn-sm fw-bold text-dark">
                                ⚙️ Kelola User
                            </a>
                            <a href="#" class="btn btn-light btn-sm fw-bold text-dark">
                                📊 Log Aktivitas Sistem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- BANNER PERINGATAN DINI (EARLY WARNING SYSTEM) -->
        <div id="earlyWarningBanner" class="row mb-4 d-none">
            <div class="col-12">
                <div id="alertBox" class="alert d-flex align-items-center justify-content-between py-3 px-4 border-0 mb-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <span id="alertIcon" class="fs-3 me-3">🚨</span>
                        <div>
                            <h6 id="alertTitle" class="fw-bold mb-1">Peringatan Keamanan Rantai Pasok!</h6>
                            <span id="alertMessage" class="small">Terdeteksi anomali risiko di jalur distribusi pelabuhan.</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" onclick="dismissBanner()" aria-label="Close"></button>
                </div>
            </div>
        </div>
        
        <!-- Baris Input Simulasi Berita & Pilihan Negara -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card p-4 bg-white">
                    <h5 class="fw-bold mb-2">Simulasi Input Berita Real-Time</h5>
                    <p class="text-muted small">Masukkan teks berita dalam Bahasa Inggris dan pilih negara tujuan untuk menganalisis sentimen risiko geopolitik secara dinamis.</p>
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select id="countrySelect" class="form-select py-2 fw-bold text-dark" onchange="updateMapLocation()">
                                <option value="Indonesia" selected>🇮🇩 Indonesia (Tanjung Priok)</option>
                                <option value="Malaysia">🇲🇾 Malaysia (Port Klang)</option>
                                <option value="Singapore">🇸🇬 Singapore (Port of Singapore)</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <input type="text" id="newsInput" class="form-control py-2" 
                                   placeholder="Ketik berita di sini..." 
                                   value="Inflation increases while exports decrease due to war.">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary fw-bold w-100 py-2" type="button" id="btnAnalyze" onclick="loadDashboardData()">
                                Analisis Risiko Berita
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Utama: Peta & Detail Status -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Visualisasi Geospasial Pelabuhan & Risiko</h5>
                        <span class="badge bg-dark text-light small">Gunakan widget kanan atas peta untuk ubah visual</span>
                    </div>
                    <div id="map"></div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card p-4 bg-white h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-dark mb-3">Status Risiko: <span id="countryName">Indonesia</span></h5>
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Berita Teranalisis:</small>
                            <p id="analyzedNews" class="small text-secondary fst-italic bg-light p-2 rounded border-start border-3 border-info">
                                "Mengambil data..."
                            </p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Metode Perhitungan:</small>
                            <span class="badge bg-secondary">Weighted Risk Model</span>
                        </div>
                    </div>
                    
                    <div class="my-3">
                        <small class="text-muted d-block">Total Skor Risiko:</small>
                        <div id="scoreContainer">
                            <h1 class="display-4 fw-bold text-warning" id="totalScore">0%</h1>
                        </div>
                        <span class="badge text-dark px-3 py-2 fs-6" id="riskStatusBadge">Mengambil data...</span>
                    </div>
                    
                    <div class="alert alert-info border-0 mb-0">
                        <h6 class="fw-bold">Rekomendasi Sistem:</h6>
                        <p class="small mb-0" id="recommendationText">Menghitung rekomendasi optimal...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Kedua: Chart & Detail Tabel -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card p-3">
                    <h5 class="fw-bold mb-3">Komponen Kontribusi Risiko (Weighted)</h5>
                    <canvas id="riskChart" style="max-height: 280px;"></canvas>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-3">Detail Indikator Komponen Risiko</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
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
                                        <td>🌤️ Weather Risk</td>
                                        <td id="rawWeather">0%</td>
                                        <td>30%</td>
                                        <td class="fw-bold text-primary" id="weightWeather">0%</td>
                                    </tr>
                                    <tr>
                                        <td>📈 Inflation Risk</td>
                                        <td id="rawInflation">0%</td>
                                        <td>20%</td>
                                        <td class="fw-bold text-danger" id="weightInflation">0%</td>
                                    </tr>
                                    <tr>
                                        <td>📰 News Sentiment Risk</td>
                                        <td id="rawNews">0%</td>
                                        <td>40%</td>
                                        <td class="fw-bold text-warning" id="weightNews">0%</td>
                                    </tr>
                                    <tr>
                                        <td>💱 Currency Risk</td>
                                        <td id="rawCurrency">0%</td>
                                        <td>10%</td>
                                        <td class="fw-bold text-success" id="weightCurrency">0%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="btn-export-area text-end mt-3">
                        <button type="button" class="btn btn-outline-dark fw-bold px-4" onclick="exportToPDF()">
                            📄 Unduh Laporan Risiko (PDF)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARIS KETIGA: TABEL RIWAYAT ANALISIS (LOG HISTORY) -->
        <div class="row g-4 mb-4 no-print">
            <div class="col-12">
                <div class="card p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">📜 Riwayat Analisis Terbaru (Log Session)</h5>
                        <button class="btn btn-sm btn-outline-danger fw-bold" onclick="clearHistory()">Hapus Riwayat</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle" id="historyTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 15%">Waktu Analisis</th>
                                    <th style="width: 15%">Negara</th>
                                    <th style="width: 40%">Teks Berita Teranalisis</th>
                                    <th style="width: 15%">Total Skor</th>
                                    <th style="width: 15%">Status Risiko</th>
                                </tr>
                            </thead>
                            <tbody id="historyBody">
                                <tr id="noHistoryRow">
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat analisis pada sesi ini. Jalankan analisis di atas untuk mulai merekam.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const portData = {
            "Indonesia": { lat: -6.1014, lng: 106.8831, name: "Pelabuhan Tanjung Priok", desc: "Hub Utama Indonesia" },
            "Malaysia": { lat: 3.0003, lng: 101.3621, name: "Port Klang", desc: "Hub Utama Malaysia" },
            "Singapore": { lat: 1.2658, lng: 103.8261, name: "Port of Singapore", desc: "Hub Internasional Singapura" }
        };

        let map;
        let currentMarker;

        // ==========================================================
        // INISIALISASI PETA & LAYER SWITCHER
        // ==========================================================
        try {
            const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            });

            const darkMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
            });

            const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });

            map = L.map('map', {
                center: [-6.1014, 106.8831],
                zoom: 5,
                layers: [streetMap] 
            });

            const baseMaps = {
                "🗺️ Peta Klasik (Default)": streetMap,
                "🌌 Peta Gelap (Dark Mode)": darkMap,
                "🛰️ Citra Satelit": satelliteMap
            };

            L.control.layers(baseMaps, null, { collapsed: false }).addTo(map);

            setMapMarker("Indonesia", "Low Risk");
        } catch(e) {
            console.error("Gagal memuat peta Leaflet:", e);
        }

        function updateMapLocation() {
            loadDashboardData();
        }

        document.title = "Laporan_Intelijen_Risiko_Rantai_Pasok";

        function setMapMarker(country, riskStatus) {
            if (!map || !portData[country]) return;
            const data = portData[country];
            if (currentMarker) { map.removeLayer(currentMarker); }

            let markerColor = "green"; 
            let pulseRGB = "40, 167, 69"; 

            if (riskStatus.includes('High')) {
                markerColor = "red";
                pulseRGB = "220, 53, 69"; 
            } else if (riskStatus.includes('Medium')) {
                markerColor = "orange";
                pulseRGB = "255, 193, 7"; 
            }

            const customIcon = L.divIcon({
                className: 'custom-pulsing-marker',
                html: `<div style="width: 20px; height: 20px; background-color: ${markerColor}; border-radius: 50%; border: 2px solid white;"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            document.documentElement.style.setProperty('--pulse-color', pulseRGB);
            map.flyTo([data.lat, data.lng], 6, { animate: true, duration: 1.5 });
            
            currentMarker = L.marker([data.lat, data.lng], { icon: customIcon }).addTo(map)
                .bindPopup(`<b>${data.name}</b><br>Tingkat Risiko: <b style="color:${markerColor}">${riskStatus}</b><br><small>${data.desc}</small>`)
                .openPopup();
        }

        let riskChart = null;

        function loadDashboardData() {
            const btn = document.getElementById('btnAnalyze');
            const newsInput = document.getElementById('newsInput');
            const countrySelect = document.getElementById('countrySelect');
            
            btn.innerText = "Menganalisis...";
            btn.disabled = true;

            document.getElementById('countryName').innerHTML = `<span class="shimmer-bg shimmer-text-short"></span>`;
            document.getElementById('analyzedNews').innerHTML = `<span class="shimmer-bg shimmer-text-long"></span>`;
            document.getElementById('totalScore').innerHTML = `<span class="shimmer-bg shimmer-score"></span>`;
            document.getElementById('riskStatusBadge').innerHTML = `<span class="shimmer-bg shimmer-text-short"></span>`;
            document.getElementById('recommendationText').innerHTML = `<span class="shimmer-bg shimmer-text-long"></span>`;

            const tableCells = ['rawWeather', 'rawInflation', 'rawNews', 'rawCurrency', 'weightWeather', 'weightInflation', 'weightNews', 'weightCurrency'];
            tableCells.forEach(id => {
                document.getElementById(id).innerHTML = `<span class="shimmer-bg shimmer-text-short" style="width:40px;"></span>`;
            });

            const countrySelected = countrySelect.value;
            const newsText = newsInput.value || "Inflation increases while exports decrease due to war.";
            const apiUrl = `{{ url('/api/risk') }}?country=${encodeURIComponent(countrySelected)}&text=${encodeURIComponent(newsText)}`;

            setTimeout(() => {
                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) throw new Error("Server error");
                        return response.json();
                    })
                    .then(data => {
                        if (data && (data.status === 'success' || data.country)) {
                            
                            const statusStr = data.risk_status || 'Low Risk';
                            setMapMarker(countrySelected, statusStr);

                            document.getElementById('countryName').innerText = data.country || countrySelected;
                            document.getElementById('analyzedNews').innerText = `"${data.news_analyzed || newsText}"`;
                            document.getElementById('totalScore').innerText = data.total_risk_score || '0%';
                            document.getElementById('recommendationText').innerText = data.recommendation || 'Tidak ada rekomendasi.';
                            
                            const badge = document.getElementById('riskStatusBadge');
                            const scoreDisplay = document.getElementById('totalScore');
                            badge.innerText = statusStr;

                            if (statusStr.includes('High')) {
                                badge.className = "badge bg-danger text-white px-3 py-2 fs-6";
                                scoreDisplay.className = "display-4 fw-bold text-danger";
                            } else if (statusStr.includes('Medium')) {
                                badge.className = "badge bg-warning text-dark px-3 py-2 fs-6";
                                scoreDisplay.className = "display-4 fw-bold text-warning";
                            } else {
                                badge.className = "badge bg-success text-white px-3 py-2 fs-6";
                                scoreDisplay.className = "display-4 fw-bold text-success";
                            }

                            const scores = data.individual_scores || {};
                            const weights = data.weighted_contributions || {};

                            document.getElementById('rawWeather').innerText = scores.weather_risk || '0%';
                            document.getElementById('rawInflation').innerText = scores.inflation_risk || '0%';
                            document.getElementById('rawNews').innerText = scores.news_sentiment_risk || '0%';
                            document.getElementById('rawCurrency').innerText = scores.currency_risk || '0%';

                            document.getElementById('weightWeather').innerText = weights.weather_contribution || '0%';
                            document.getElementById('weightInflation').innerText = weights.inflation_contribution || '0%';
                            document.getElementById('weightNews').innerText = weights.news_contribution || '0%';
                            document.getElementById('weightCurrency').innerText = weights.currency_contribution || '0%';

                            const wWeather = parseFloat(weights.weather_contribution || 0);
                            const wInflation = parseFloat(weights.inflation_contribution || 0);
                            const wNews = parseFloat(weights.news_contribution || 0);
                            const wCurrency = parseFloat(weights.currency_contribution || 0);

                            try {
                                const ctx = document.getElementById('riskChart').getContext('2d');
                                if (riskChart) { riskChart.destroy(); }
                                
                                riskChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Weather (30%)', 'Inflation (20%)', 'News Sentiment (40%)', 'Currency (10%)'],
                                        datasets: [{
                                            label: 'Kontribusi Persentase Risiko (%)',
                                            data: [wWeather, wInflation, wNews, wCurrency],
                                            backgroundColor: [
                                                'rgba(54, 162, 235, 0.8)',
                                                'rgba(255, 99, 132, 0.8)',
                                                'rgba(255, 206, 86, 0.8)',
                                                'rgba(75, 192, 192, 0.8)'
                                            ],
                                            borderRadius: 6
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: { y: { beginAtZero: true, max: 40 } }
                                    }
                                });
                            } catch(chartError) {
                                console.error("Gagal menggambar chart:", chartError);
                            }

                            updateAlertBanner(countrySelected, statusStr);
                            addHistoryRow(countrySelected, data.news_analyzed || newsText, data.total_risk_score, statusStr);
                        }
                    })
                    .catch(error => console.error('Koneksi API gagal:', error))
                    .finally(() => {
                        btn.innerText = "Analisis Risiko Berita";
                        btn.disabled = false;
                    });
            }, 800);
        }

        function updateAlertBanner(country, status) {
            const banner = document.getElementById('earlyWarningBanner');
            const alertBox = document.getElementById('alertBox');
            const alertIcon = document.getElementById('alertIcon');
            const alertTitle = document.getElementById('alertTitle');
            const alertMessage = document.getElementById('alertMessage');

            banner.classList.remove('d-none');

            if (status.includes('High')) {
                alertBox.className = "alert alert-danger d-flex align-items-center justify-content-between py-3 px-4 border-0 mb-0 shadow-sm pulse-alert bg-danger text-white";
                alertIcon.innerText = "🚨";
                alertTitle.innerText = `SIAGA SATU (CRITICAL RISK) - Pelabuhan ${country}!`;
                alertMessage.innerText = `Jalur logistik terancam lumpuh akibat sentimen risiko ekstrem di wilayah sekitar pelabuhan. Segera cari rute alternatif!`;
            } else if (status.includes('Medium')) {
                alertBox.className = "alert alert-warning d-flex align-items-center justify-content-between py-3 px-4 border-0 mb-0 shadow-sm bg-warning text-dark";
                alertIcon.innerText = "⚠️";
                alertTitle.innerText = `PERINGATAN (MODERATE RISK) - Pelabuhan ${country}`;
                alertMessage.innerText = `Kondisi keamanan fluktuatif. Disarankan memantau perkembangan situasi secara berkala.`;
            } else {
                banner.classList.add('d-none');
            }
        }

        function dismissBanner() {
            document.getElementById('earlyWarningBanner').classList.add('d-none');
        }

        function addHistoryRow(country, news, score, status) {
            const body = document.getElementById('historyBody');
            const noHistoryRow = document.getElementById('noHistoryRow');
            
            if (noHistoryRow) {
                noHistoryRow.remove();
            }

            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            let statusBadgeClass = "bg-success";
            if (status.includes('High')) {
                statusBadgeClass = "bg-danger";
            } else if (status.includes('Medium')) {
                statusBadgeClass = "bg-warning text-dark";
            }

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><span class="badge bg-secondary">${timeStr}</span></td>
                <td><strong>${country}</strong></td>
                <td class="text-truncate small" style="max-width: 300px;" title="${news}">"${news}"</td>
                <td class="fw-bold">${score}</td>
                <td><span class="badge ${statusBadgeClass}">${status}</span></td>
            `;

            body.insertBefore(newRow, body.firstChild);
        }

        function clearHistory() {
            const body = document.getElementById('historyBody');
            body.innerHTML = `
                <tr id="noHistoryRow">
                    <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat analisis pada sesi ini. Jalankan analisis di atas untuk mulai merekam.</td>
                </tr>
            `;
        }

        function exportToPDF() {
            const today = new Date().toLocaleDateString('id-ID', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute:'2-digit'
            });
            document.body.setAttribute('data-print-date', today);
            window.print();
        }

        window.onload = loadDashboardData;
    </script>
</body>
</html>