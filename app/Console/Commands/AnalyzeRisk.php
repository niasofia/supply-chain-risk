<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AnalyzeRisk extends Command
{
    // Perintah yang nanti diketik di terminal
    protected $signature = 'risk:analyze {country=my} {--news=}';

    protected $description = 'Simulasi analisis risiko rantai pasok global lewat terminal';

    public function handle()
    {
        $countryInput = strtolower($this->argument('country'));
        $newsInput = $this->option('news') ?? "Rising inflation rates and sudden currency depreciation are causing delays.";

        // Data Pelabuhan Mockup
        $ports = [
            'id' => ['name' => 'Indonesia (Tanjung Priok)', 'lat' => -6.1018, 'lng' => 106.8824],
            'my' => ['name' => 'Malaysia (Port Klang)', 'lat' => 3.0000, 'lng' => 101.4000],
            'sg' => ['name' => 'Singapore (Port of Singapore)', 'lat' => 1.2644, 'lng' => 103.8400],
        ];

        if (!array_key_exists($countryInput, $ports)) {
            $this->error(" Kode negara tidak valid! Pilih antara: id, my, atau sg.");
            return;
        }

        $selectedPort = $ports[$countryInput];

        // Simulasi perhitungan skor risiko
        $weatherRisk = 12; // 40% * 30%
        $inflationRisk = 10; // 50% * 20%
        $newsRisk = 40; // 100% * 40%
        $currencyRisk = 3; // 30% * 10%
        $totalScore = $weatherRisk + $inflationRisk + $newsRisk + $currencyRisk;

        // Output Tampilan Terminal
        $this->info("==================================================================");
        $this->info("   RISK INTELLIGENCE - PLATFORM MONITORING RANTAI PASOK GLOBAL    ");
        $this->info("==================================================================");
        
        $this->line("<fg=yellow;bg=black> [PERINGATAN] Status: MODERATE RISK - {$selectedPort['name']} </>");
        $this->newLine();

        $this->line("<options=bold>📍 Lokasi Pelabuhan :</> {$selectedPort['name']} (Lat: {$selectedPort['lat']}, Lng: {$selectedPort['lng']})");
        $this->line("<options=bold>📰 Berita Teranalisis:</> \"{$newsInput}\"");
        $this->line("<options=bold>⚙️  Metode Perhitungan:</> Weighted Risk Model");
        $this->line("<options=bold>📊 Total Skor Risiko  :</> <fg=yellow;options=bold>{$totalScore}% (Medium Risk / Risiko Sedang)</>");
        
        $this->newLine();
        $this->info("--- Rincian Komponen Kontribusi Risiko ---");
        
        // Menampilkan tabel indikator di terminal
        $this->table(
            ['Indikator Risiko', 'Skor Awal', 'Bobot', 'Kontribusi Akhir'],
            [
                ['Weather Risk', '40%', '30%', '12%'],
                ['Inflation Risk', '50%', '20%', '10%'],
                ['News Sentiment Risk', '100%', '40%', '40%'],
                ['Currency Risk', '30%', '10%', '3%'],
            ]
        );

        $this->warn("💡 Rekomendasi Sistem: Lakukan pemantauan berkala pada cuaca dan kondisi geopolitik daerah transit.");
        $this->info("==================================================================");
    }
}