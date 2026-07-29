# 🌍 Global Supply Chain Risk Intelligence Platform

Platform Monitoring & Analitik Risiko Rantai Pasok Global berbasis **Laravel 12**, **Multi-API Integration**, **Geospatial Visualization (Leaflet.js)**, dan **AI Lexicon Sentiment Analysis**.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Production-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Railway](https://img.shields.io/badge/Railway-Deployed-0B0D0E?style=for-the-badge&logo=railway&logoColor=white)

---

## 🚀 Fitur Utama Project

* 🗺️ **Geospatial Port Visualization**: Visualisasi interaktif pelabuhan utama dunia menggunakan Leaflet.js & OpenStreetMap.
* 📊 **Risk Scoring Engine (Weighted Model)**: Perhitungan skor risiko rantai pasok berbasis pembobotan Cuaca (30%), Inflasi (20%), Sentimen Berita (40%), dan Kurs Mata Uang (10%).
* 🤖 **AI Lexicon Sentiment Analysis**: Analisis sentimen berita logistik & geopolitik otomatis (Positif, Netral, Negatif) menggunakan algoritma PHP Lexicon.
* 👑 **Dual-Role Access System**:
  * **Admin Panel Control Center**: Hak akses penuh untuk manajemen pengguna & memoderasi dataset indikator risiko.
  * **User Mode**: Layanan monitoring publik & pencarian risiko interaktif.
* 📡 **REST API Ready**: Menyediakan 5 endpoint data publik (`/api/countries`, `/api/risk`, `/api/ports`, `/api/news`, `/api/currency`).

---

## 🔑 Akun Demo Terdaftar

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| 👑 **Admin** | `adminbaru@gmail.com` | `admin12345` | Akses Penuh + Manajemen User & CRUD Risiko |
| 👤 **User** | `userbaru@gmail.com` | `user12345` | Monitoring Rantai Pasok & Simulasi Risiko |

---

## ⚙️ Syarat Sistem (System Requirements)

Sebelum melakukan penginstalan, pastikan perangkat Anda telah terpasang:
* **PHP** >= 8.2
* **Composer** >= 2.0
* **Node.js** >= 18.x / 20.x & **NPM**
* **Git**

---

## 💻 Panduan Penginstalan & Jalankan Lokal (Step-by-Step)

Ikuti langkah-langkah berikut untuk menginstall dan menjalankan aplikasi di komputer lokal Anda:

### 1. Clone Repository
Buka terminal / Command Prompt, lalu jalankan:
```bash
git clone https://github.com/niasofia/supply-chain-risk.git
cd supply-chain-risk
```

### 2. Install PHP Dependencies (Composer)
```bash
composer install
```

### 3. Install Node.js Dependencies (NPM)
```bash
npm install
```

### 4. Salin File Environment (.env)
Salin file template `.env.example` menjadi `.env`:
* **Di Windows (PowerShell / CMD)**:
  ```cmd
  copy .env.example .env
  ```
* **Di Linux / macOS**:
  ```bash
  cp .env.example .env
  ```

### 5. Generate Application Encryption Key
```bash
php artisan key:generate
```

### 6. Migrasi & Seed Database (Otomatis Buat 79+ Data Negara & User)
Jalankan migrasi database beserta seeder data negara, kata sentimen, dan akun admin:
```bash
php artisan migrate:fresh --seed
```

### 7. Kompilasi Aset Frontend (Vite)
Kompilasi berkas CSS & JavaScript:
```bash
npm run build
```

### 8. Jalankan Server Lokal Laravel
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```

### 9. Buka Aplikasi di Browser
Akses aplikasi melalui alamat web lokal berikut:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🌐 Panduan Hosting & Deployment ke Railway Cloud

Project ini sudah dilengkapi file konfigurasi produksi **Docker Engine + Nginx + PHP-FPM** ([Dockerfile](Dockerfile), [nixpacks.toml](nixpacks.toml), [railway.json](railway.json)).

### Langkah Deploy ke Railway:
1. Push kodingan ke repository GitHub Anda (`git push origin main`).
2. Buka [Railway Dashboard](https://railway.app/dashboard) -> Klik **+ New Project** -> Pilih **Deploy from GitHub repo**.
3. Hubungkan repository `niasofia/supply-chain-risk`.
4. Masuk ke tab **Variables** di Railway, klik **Raw Editor**, lalu paste konfigurasi ini:
   ```env
   APP_NAME="GlobalTrade Insight"
   APP_ENV=production
   APP_KEY=base64:YEes+YVOgQ6LlkKpXxbWU8z2Hm4Amp8zkLxtsIk68dQ=
   APP_DEBUG=false
   APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
   DB_CONNECTION=sqlite
   PORT=8080
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   ```
5. Klik **Deploy**. Railway akan otomatis melakukan build image Docker Nginx + PHP-FPM dan aplikasi Anda langsung aktif online!

---

## 📡 Dokumentasi REST API

| Method | Endpoint | Deskripsi |
|---|---|---|
| `GET` | `/api/countries` | Mengambil data 79+ negara, pelabuhan, & koordinat |
| `GET` | `/api/risk` | Mengambil daftar indikator & skor risiko |
| `GET` | `/api/ports` | Mengambil dataset lokasi pelabuhan dunia |
| `GET` | `/api/news` | Analisis sentimen berita logistik & geopolitik (Lexicon) |
| `GET` | `/api/currency` | Data kurs nilai tukar mata uang global real-time |

---

## 📄 Lisensi

Project ini dibuat untuk memenuhi tugas **Project Final Global Supply Chain Risk Intelligence Platform**. Bebas digunakan dan dikembangkan kembali di bawah lisensi MIT.
