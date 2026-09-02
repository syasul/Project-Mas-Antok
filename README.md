# 🛡️ Sistem Integrasi & Pemantauan Keamanan Poltekad Kodiklatad
### *Secure Control Center & Unified Security Gateway*

Dokumentasi dan sistem dashboard pusat kendali keamanan terpadu berbasis Laravel 11 untuk Politeknik Angkatan Darat (Poltekad) Kodiklatad.

---

## 📌 Ringkasan Proyek

| Informasi | Deskripsi |
| :--- | :--- |
| **Nama Proyek** | Sistem Integrasi & Pemantauan Keamanan Terpadu (Command Center Dashboard) |
| **Institusi** | Politeknik Angkatan Darat (Poltekad) Kodiklatad |
| **Framework Backend** | Laravel 11 / PHP 8.2+ |
| **Frontend & UI** | Blade Views, Vanilla JavaScript, Tailwind CSS, Canvas 2D Engine, SVG Map Blueprint, Leaflet GIS Engine, Web Audio API |
| **Database** | SQLite / MySQL (Eloquent ORM) |
| **Protokol Terintegrasi** | REST API, WebSocket (Simulasi), MQTT (Simulasi) via *Unified Gateway* |
| **Mesin Keputusan** | Rule-Based Expert System (IF-THEN Logic) dengan Pencatatan Otomatis |

Untuk rincian status fitur lengkap, checklist implementasi, dan roadmap backlog, silakan merujuk ke berkas:
👉 **[TASK.md](TASK.md)**

---

## 🛠️ Panduan Menjalankan Sistem

### Persyaratan Sistem
- PHP >= 8.2 (ekstensi `pdo_sqlite` / `pdo_mysql`, `curl`, `mbstring`, `openssl`)
- Composer
- Node.js & NPM

### Langkah Menjalankan Aplikasi
```bash
# 1. Masuk ke direktori proyek
cd project-dashboard-sistem-keamanan-poltekad

# 2. Instalasi dependensi PHP & JavaScript (jika belum)
composer install
npm install

# 3. Konfigurasi Environment & Database
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed

# 4. Menjalankan Server Pengembangan
php artisan serve
```
Akses dashboard pada peramban melalui alamat: **`http://127.0.0.1:8000`**

### Kredensial Akun Multi-Role (RBAC)
- **Super Admin (Komandan Pusat Kendali):** `admin@poltekad.mil.id` / `poltekad123`
- **Operator Pusat Komando:** `operator@poltekad.mil.id` / `poltekad123`
- **Komandan Sektor Pertahanan:** `sektor@poltekad.mil.id` / `poltekad123`
- **Auditor Intelijen & Siber:** `auditor@poltekad.mil.id` / `poltekad123`

---

## 🧪 Perintah Pengujian Otomatis

Sistem dilengkapi dengan command Artisan untuk pengujian performa, keandalan gateway, dan mesin keputusan:

```bash
# 1. Uji Lapisan Komunikasi (1.000 Pesan Ingestion & Latensi P95)
php artisan test:communication-layer

# 2. Uji Akurasi Decision Engine (50 Skenario Aturan Keamanan)
php artisan test:decision-engine

# 3. Uji Kinerja & Konkurensi Backend (50 Koneksi Simultan)
php artisan test:backend-performance
```

---

## 📁 Struktur Modul Utama

```
project-dashboard-sistem-keamanan-poltekad/
├── app/
│   ├── Console/Commands/       # Command test otomatis (Communication, Decision, Backend)
│   ├── Http/Controllers/       # GatewayController, AuthController, DashboardApiController, ReportExportController
│   ├── Models/                 # User, SensorLog, DecisionLog, SecurityEvent
│   └── Services/               # UnifiedGateway, DecisionEngine
├── database/
│   ├── migrations/             # Tabel sensor_logs, decision_logs, security_events, users
│   └── seeders/                # DatabaseSeeder (Operator Multi-Role, Log Sensor, Decision Engine)
├── resources/views/
│   ├── login.blade.php         # Halaman login antarmuka taktis
│   ├── welcome.blade.php       # Shell tata letak dashboard utama
│   ├── reports/
│   │   └── incident-pdf.blade.php # Format cetak laporan resmi militer
│   └── partials/               # Tab Overview, Camera, Drone, Perimeter, IoT, Turret, Decision
├── TASK.md                     # Rincian Task List, Status & Backlog Roadmap
└── README.md                   # Dokumentasi Utama Proyek
```
