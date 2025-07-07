# Sentosa Hospital Management System

Sistem manajemen rumah sakit berbasis web yang dikembangkan untuk memudahkan pengelolaan data pasien, dokter, janji temu, departemen, dan administrasi rumah sakit secara terintegrasi.

## Fitur Utama

- **Manajemen Pasien:** Tambah, edit, hapus, dan cari data pasien.
- **Manajemen Dokter:** Kelola data dokter beserta departemen dan spesialisasi.
- **Janji Temu (Appointments):** Atur jadwal, status, dan catatan janji temu pasien dengan dokter.
- **Departemen:** Kelola departemen rumah sakit dan keterkaitannya dengan dokter.
- **Autentikasi & Role:** Sistem login, register, dan hak akses berdasarkan role (admin, doctor, staff).
- **Dashboard Statistik:** Statistik jumlah pasien, dokter, janji temu, dan grafik tren kunjungan.
- **Responsive Design:** Tampilan modern dan responsif berbasis Bootstrap.
- **Kalkulator Kesehatan:** Fitur interaktif untuk menghitung skor kesehatan sederhana.
- **About & Footer:** Informasi profil rumah sakit, visi-misi, dan kontak.

## Teknologi yang Digunakan

- **Backend:** PHP (mysqli)
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
- **Database:** MySQL/MariaDB
- **Library:** Chart.js, Bootstrap Icons

## Instalasi & Penggunaan

1. **Clone Repository**
   ```
   git clone https://github.com/username/namaproject.git
   ```

2. **Konfigurasi Database**
   - Import file `aquorder_db_rio.sql` ke MySQL/MariaDB Anda.
   - Edit file `assets/config/config.php` untuk menyesuaikan konfigurasi database.

3. **Jalankan di Web Server**
   - Pastikan folder `assets` berada di dalam root web server (misal: `htdocs`/`public_html`).
   - Akses melalui browser: `http://localhost/assets/login.php`

4. **Akun Default**
   - Buat akun melalui halaman register atau gunakan data pada tabel `users` di database.

## Struktur Folder

```
assets/
├── config/
│   └── config.php
├── includes/
│   ├── navbar.php
│   └── footer.php
├── pages/
│   ├── patients.php
│   ├── doctors.php
│   ├── appointments.php
│   ├── departments.php
│   └── about.php
├── assets/
│   ├── css/
│   │   └── custom.css
│   └── js/
│       └── custom.js
├── aquorder_db_rio.sql
├── index.php
├── login.php
├── register.php
├── logout.php
└── ...
```

## Kontribusi

Kontribusi sangat terbuka! Silakan fork repository ini dan ajukan pull request untuk fitur atau perbaikan bug.

## Lisensi

Proyek ini untuk keperluan portofolio dan pembelajaran. Silakan gunakan dan modifikasi sesuai kebutuhan.

## Kontak Pengembang

- **Nama:** Andreas Rio Christian
- **Email:** [info@hospital.com](mailto:riochristian36@gmail.com)

---

> Sistem ini dikembangkan sebagai bagian dari pembelajaran dan portofolio pengembangan aplikasi web modern untuk manajemen rumah sakit.
