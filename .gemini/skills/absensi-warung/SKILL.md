---
name: absensi-warung
description: "Manage, develop, test, and troubleshoot the Laravel PWA Geofencing Attendance System for Ayam Bebek GANJ'S 'Cak Ali'. Activate this skill when modifying attendance flows, database models, geofencing logic, weekly scheduling, or PWA integration in this project."
---

# Absensi Warung — Developer Skill Guide

Panduan instruksi, arsitektur, dan perintah pemeliharaan untuk mengelola proyek PWA Absensi Karyawan berbasis Geolocation di **Ayam Bebek GANJ'S "Cak Ali"**.

---

## 1. Arsitektur Utama Proyek

Proyek ini dibangun menggunakan **Laravel 11 Monolith** dengan frontend reaktif berbasis **Livewire 3** dan **Alpine.js**. Aplikasi ini dikonfigurasi sebagai **Progressive Web App (PWA)** agar dapat diinstal di handphone karyawan.

### Komponen & Lokasi File Penting:

*   **Model Database (`app/Models/`)**:
    *   [User](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Models/User.php): Menyimpan kredensial pengguna, role (`admin` / `karyawan`), dan relasi.
    *   [Karyawan](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Models/Karyawan.php): Menyimpan data detail kepegawaian, NIK, gaji pokok, uang makan per hari, dan lokasi absen yang ditugaskan (`id_lokasi`).
    *   [LokasiKantor](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Models/LokasiKantor.php): Data koordinat latitude, longitude, dan radius geofence dalam meter.
    *   [Shift](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Models/Shift.php): Pengaturan jam kerja (mulai, selesai, dan toleransi keterlambatan dalam menit).
    *   [JadwalKaryawan](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Models/JadwalKaryawan.php): Menyimpan penugasan shift karyawan per tanggal kerja.
    *   [Absensi](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Models/Absensi.php): Catatan log kehadiran karyawan (waktu masuk, waktu keluar, latitude/longitude masuk & keluar, status kehadiran, dan uang makan diterima).
    *   [LaporanGaji](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Models/LaporanGaji.php): Rekapitulasi bulanan kehadiran, total uang makan, total potongan, dan gaji bersih.

*   **Logika Bisnis / Services (`app/Services/`)**:
    *   [GeofenceService](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Services/GeofenceService.php): Validasi radius menggunakan rumus Haversine. Jika karyawan memiliki lokasi khusus (`id_lokasi` tidak null), validasi dilakukan terhadap lokasi tersebut. Jika null, validasi dilakukan terhadap lokasi aktif default (`LokasiKantor::aktif()`).
    *   [AbsensiService](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Services/AbsensiService.php): Menentukan status kehadiran (hadir tepat waktu atau terlambat) dan memproses transaksi absen masuk/keluar.
    *   [PayrollService](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/app/Services/PayrollService.php): Mengkalkulasi gaji bulanan berdasarkan kehadiran harian dan potongan keterlambatan.

*   **Livewire Frontend (`app/Livewire/` & `resources/views/livewire/`)**:
    *   `Absensi\AbsenMasuk`: Tombol absen masuk berbentuk lingkaran dengan animasi pulse. Membaca GPS browser HP dan mengirimkannya ke backend.
    *   `Absensi\AbsenKeluar`: Tombol absen keluar terintegrasi GPS.
    *   `Karyawan\RiwayatKehadiran`: Riwayat bulanan absensi karyawan dilengkapi filter bulan & tahun.
    *   `Admin\DashboardSummary`: Ringkasan statistik kehadiran realtime untuk Admin.

---

## 2. Perintah Pemeliharaan (Command Line)

Gunakan perintah-perintah berikut untuk mengelola server, database, dan cache project:

### 2.1 Manajemen Server Background
Gunakan script manajer server [server.sh](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/server.sh) untuk menjalankan server secara permanen di background (bahkan saat terminal ditutup):
*   **Jalankan Server**: `./server.sh start`
*   **Hentikan Server**: `./server.sh stop`
*   **Cek Status Server**: `./server.sh status`
*   **Lihat Log Server**: `./server.sh log` (Tekan `Ctrl+C` untuk keluar)

### 2.2 Tunneling HTTPS (Akses HP & Validasi GPS)
Browser HP membutuhkan koneksi HTTPS aman untuk mengizinkan pembacaan GPS. Jalankan script [tunnel.sh](file:///Users/mac/Documents/Penelitian%20Ilmiah/web_absensi_warung/tunnel.sh) untuk mengekspos localhost ke publik secara aman:
```bash
./tunnel.sh
```
*Gunakan link HTTPS yang dihasilkan untuk dibuka di browser HP Anda.*

### 2.3 Reset Database & Seeder
MAMP menggunakan port **8889** dan password `root`. Jalankan migrasi fresh beserta seeder default:
```bash
php artisan migrate:fresh --seed
```

### 2.4 Pembersihan Cache
Apabila ada perubahan route atau view yang tidak langsung terlihat:
```bash
php artisan route:clear && php artisan view:clear && php artisan cache:clear
```

---

## 3. Aturan Bisnis Penting

### 3.1 Aturan Keterlambatan & Uang Makan:
*   Karyawan dinyatakan **terlambat** jika waktu melakukan absensi masuk melewati batas: `jam_mulai shift + toleransi_menit`.
*   **Uang Makan Harian**:
    *   Jika status **Hadir** (tepat waktu) -> Uang makan diberikan penuh (sesuai nominal per hari).
    *   Jika status **Terlambat** atau **Tidak Hadir** -> Karyawan mendapat **Rp 0** (uang makan hangus hari itu).

### 3.2 Penjadwalan Kerja Mingguan (Weekly Grid):
*   Jadwal diatur per minggu (Senin s.d. Minggu) di menu **Jadwal** Admin.
*   Pilihan shift pada hari tertentu akan **dikunci otomatis** (badge hijau "Sudah Absen" 🔒) apabila karyawan telah melakukan absen masuk pada hari tersebut. Ini bertujuan untuk mencegah manipulasi jadwal secara retrospektif.

### 3.3 Instalasi Aplikasi (PWA):
*   Aplikasi dapat diinstal langsung ke HP melalui banner **"Pasang Aplikasi Absensi"** yang muncul di bagian atas halaman (pada browser seluler biasa).
*   Di **Android**, banner memicu prompt instalasi native Chrome.
*   Di **iOS**, banner menunjukkan panduan visual langkah demi langkah untuk menggunakan menu *Share* -> *Add to Home Screen*.
*   Banner otomatis hilang saat aplikasi dijalankan di *standalone mode*.
