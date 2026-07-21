<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Monitoring - Global Risk Intelligence</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f6f9; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-globe2 text-primary me-2"></i>Supply Chain Risk Intelligence
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0"><i class="bi bi-flag-fill text-primary me-2"></i>Global Country Intelligence</h4>

            <!-- Form Pilih Negara -->
            <form action="{{ route('country.monitoring') }}" method="GET" class="d-flex gap-2">
                <select name="country" class="form-select fw-semibold" onchange="this.form.submit()">
                    <option value="Indonesia" {{ ($countryName ?? '') == 'Indonesia' ? 'selected' : '' }}>Indonesia 🇮🇩</option>
                    <option value="Germany" {{ ($countryName ?? '') == 'Germany' ? 'selected' : '' }}>Germany 🇩🇪</option>
                    <option value="China" {{ ($countryName ?? '') == 'China' ? 'selected' : '' }}>China 🇨🇳</option>
                    <option value="Australia" {{ ($countryName ?? '') == 'Australia' ? 'selected' : '' }}>Australia 🇦🇺</option>
                    <option value="Japan" {{ ($countryName ?? '') == 'Japan' ? 'selected' : '' }}>Japan 🇯🇵</option>
                    <option value="United States" {{ ($countryName ?? '') == 'United States' ? 'selected' : '' }}>United States 🇺🇸</option>
                </select>
            </form>
        </div>

        <div class="row g-4">
            <!-- Profil Negara -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-info-circle-fill text-info me-2"></i> Profil Negara (REST Countries API)
                    </div>
                    <div class="card-body">
                        @if(isset($countryData) && $countryData)
                            <div class="d-flex align-items-center gap-3 mb-3">
                                @if(isset($countryData['flags']['png']))
                                    <img src="{{ $countryData['flags']['png'] }}" alt="Flag" style="width: 60px;" class="border rounded">
                                @endif
                                <div>
                                    <h5 class="fw-bold m-0">{{ $countryData['name']['common'] ?? $countryName }}</h5>
                                    <small class="text-muted">Ibu Kota: {{ $countryData['capital'][0] ?? 'N/A' }}</small>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Wilayah / Region:</span>
                                    <strong>{{ $countryData['region'] ?? 'N/A' }} ({{ $countryData['subregion'] ?? '' }})</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Populasi:</span>
                                    <strong>{{ number_format($countryData['population'] ?? 0) }} Jiwa</strong>
                                </li>
                            </ul>
                        @else
                            <div class="alert alert-warning small m-0">Gagal mengambil data profil negara dari API.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Monitor Cuaca -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-cloud-sun-fill text-warning me-2"></i> Monitor Cuaca Real-Time (Open-Meteo API)
                    </div>
                    <div class="card-body">
                        @if(isset($weatherData) && $weatherData)
                            <div class="text-center py-2">
                                <i class="bi bi-thermometer-half display-3 text-danger"></i>
                                <h2 class="fw-bold mt-2">{{ $weatherData['temperature'] ?? '-' }} °C</h2>
                                <p class="text-muted mb-0">Temperatur Terkini</p>
                            </div>
                            <div class="row text-center mt-3 pt-3 border-top small">
                                <div class="col-6">
                                    <span class="text-muted">Kecepatan Angin:</span>
                                    <h6 class="fw-bold mt-1">{{ $weatherData['windspeed'] ?? '-' }} km/h</h6>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Arah Angin:</span>
                                    <h6 class="fw-bold mt-1">{{ $weatherData['winddirection'] ?? '-' }}°</h6>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning small m-0">Gagal mengambil data cuaca real-time dari API.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>