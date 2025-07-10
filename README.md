<p align="center">
    <img src="https://laravel.com/img/logomark.min.svg" width="150" alt="SETUKPA Logo">
</p>

<h1 align="center">SIAKAD SETUKPA</h1>
<p align="center">Sistem Informasi Akademik - Sekolah Pembentukan Perwira</p>

<p align="center">
<a href="https://github.com/laravel/framework"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel" alt="Laravel Version"></a>
<a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php" alt="PHP Version"></a>
<a href="https://getbootstrap.com"><img src="https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=flat&logo=bootstrap" alt="Bootstrap Version"></a>
<img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## 📋 Tentang Sistem Ini

SIAKAD SETUKPA (Sistem Informasi Akademik - Sekolah Pembentukan Perwira) adalah aplikasi web berbasis Laravel untuk mengelola data akademik siswa, mata pelajaran, dan penilaian. Sistem ini dirancang untuk memudahkan institusi pendidikan dalam mengelola proses akademik secara digital dan efisien.

### ✨ Fitur Utama

- **📚 Manajemen Siswa**
  - Pendaftaran dan pengelolaan data siswa
  - Profil lengkap dengan informasi pribadi
  - Riwayat akademik dan nilai
  - Laporan prestasi siswa

- **📖 Manajemen Mata Pelajaran**
  - Pengelolaan kurikulum dan mata pelajaran
  - Pengaturan SKS dan semester
  - Statistik mata pelajaran
  - Analisis tingkat kelulusan

- **🎯 Sistem Penilaian**
  - Input nilai dengan validasi otomatis
  - Konversi nilai ke grade (A, B, C, D, E)
  - Sistem validasi nilai oleh manajemen
  - Laporan kartu hasil studi

- **👥 Manajemen Pengguna**
  - Role-based access control (Admin, Manajemen, Dosen, Siswa)
  - Sistem autentikasi yang aman
  - Profil pengguna yang dapat dikustomisasi

- **📊 Dashboard & Laporan**
  - Dashboard interaktif dengan statistik real-time
  - Laporan komprehensif dalam berbagai format
  - Grafik dan visualisasi data
  - Export data ke Excel/PDF

- **🔔 Sistem Notifikasi**
  - Notifikasi real-time untuk aktivitas penting
  - Pemberitahuan validasi nilai
  - Riwayat aktivitas pengguna

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 11.x
- **Frontend**: Bootstrap 5, jQuery, Chart.js
- **Database**: MySQL 8.0+
- **PHP Version**: 8.2+
- **Server**: Apache/Nginx

## 📦 Instalasi

### Prasyarat

Pastikan sistem Anda memiliki:
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL 8.0+
- Node.js & NPM (untuk asset compilation)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/your-username/web-setukpa.git
   cd web-setukpa
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=setukpa_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Migration & Seeding**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compile Assets**
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

   Akses aplikasi di `http://localhost:8000`

## 👤 Default User Accounts

Setelah seeding, Anda dapat login dengan akun berikut:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@setukpa.com | password |
| Manajemen | manager@setukpa.com | password |
| Dosen | dosen@setukpa.com | password |
| Siswa | siswa@setukpa.com | password |

## 📖 Panduan Penggunaan

### Untuk Admin
- Mengelola seluruh sistem
- Membuat dan mengedit pengguna
- Mengatur mata pelajaran dan kurikulum
- Memvalidasi nilai yang diinput dosen

### Untuk Manajemen
- Mengelola data siswa
- Melihat laporan dan statistik
- Memvalidasi nilai
- Mengatur mata pelajaran

### Untuk Dosen
- Menginput nilai siswa
- Melihat data siswa yang diampu
- Mengupdate nilai yang sudah diinput
- Melihat statistik kelas

### Untuk Siswa
- Melihat dashboard dengan informasi umum
- Melihat daftar dan detail mata pelajaran
- Melihat jadwal pelajaran
- Mengelola profil pribadi

## 🔧 Konfigurasi

### File Storage
Pastikan direktori berikut memiliki permission write:
```bash
chmod -R 775 storage bootstrap/cache
```

### Queue Configuration
Untuk menjalankan job queue (notifikasi):
```bash
php artisan queue:work
```

### Scheduled Tasks
Tambahkan cron job untuk task scheduling:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 📚 API Documentation (Belum Fixed)

Sistem menyediakan RESTful API untuk integrasi dengan sistem lain:

### Authentication
```bash
POST /api/login
POST /api/logout
```

### Students
```bash
GET    /api/students
POST   /api/students
GET    /api/students/{id}
PUT    /api/students/{id}
DELETE /api/students/{id}
```

### Subjects
```bash
GET    /api/subjects
POST   /api/subjects
GET    /api/subjects/{id}
PUT    /api/subjects/{id}
DELETE /api/subjects/{id}
```

### Scores
```bash
GET    /api/scores
POST   /api/scores
GET    /api/scores/{id}
PUT    /api/scores/{id}
DELETE /api/scores/{id}
```

## 🧪 Testing

Menjalankan test suite:
```bash
# Unit tests
php artisan test

# Feature tests dengan coverage
php artisan test --coverage

# Specific test file
php artisan test tests/Feature/StudentTest.php
```

## 📁 Struktur Project
````
