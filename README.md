# 📍 SmartGeo HRIS - Sistem Presensi & Penggajian (Laravel 12)

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-563D7C?style=for-the-badge&logo=bootstrap)
![AdminLTE](https://img.shields.io/badge/AdminLTE-v4-343a40?style=for-the-badge)
![PWA](https://img.shields.io/badge/PWA-Ready-blue?style=for-the-badge&logo=pwa)

**SmartGeo HRIS** adalah aplikasi manajemen sumber daya manusia (HR) modern yang berfokus pada validitas data kehadiran dan otomatisasi penggajian. Aplikasi ini dirancang khusus untuk mencegah kecurangan absensi menggunakan teknologi **GPS Geofencing** dan **Verifikasi Selfie**, serta mengintegrasikan seluruh siklus karyawan mulai dari rekrutmen hingga slip gaji digital.

Aplikasi ini juga sudah mendukung **PWA (Progressive Web App)**, sehingga bisa diinstal di HP Android/iOS layaknya aplikasi native.

---

## 🌟 Fitur Unggulan

### 1. Absensi Anti-Fraud (Anti-Curang)
* **GPS Geofencing Server-Side:** Validasi jarak lokasi pegawai vs kantor dilakukan di sisi server menggunakan *Haversine Formula*. Aman dari manipulasi client-side.
* **Liveness Selfie:** Wajib mengambil foto langsung dari kamera (Webcam/HP), tidak bisa upload file dari galeri untuk mencegah manipulasi.
* **Smart Attendance Rules:** Sistem otomatis menolak absen jika dilakukan di luar radius kantor atau di luar jam yang ditentukan.
* **Multi-Office Support:** Admin dapat mengatur banyak lokasi kantor dengan titik koordinat dan radius toleransi yang berbeda-beda.

### 2. Penggajian Otomatis (Smart Payroll)
* **Kalkulasi Gaji Bersih:** Sistem menghitung otomatis: `(Gaji Pokok + Tunjangan + Uang Makan + Lembur) - (BPJS + Pajak + Denda Telat + Cicilan Kasbon)`.
* **Logic "No Work No Pay":** Pegawai yang tidak memiliki data absensi sama sekali dalam satu bulan tidak akan ter-generate gajinya (kecuali level Direktur).
* **Slip Gaji Digital:** Cetak slip gaji otomatis dalam format PDF yang rapi.

### 3. Manajemen Birokrasi Digital
* **Lembur Terkontrol:** Pegawai mengajukan lembur -> Manager melakukan Approval. Durasi lembur Weekday dibatasi sistem (Max 2 Jam) untuk efisiensi budget perusahaan.
* **Cuti & Izin:** Pengajuan cuti minimal H-3. Upload bukti surat dokter untuk izin sakit.
* **Kasbon (Pinjaman):** Validasi otomatis limit cicilan (Max 30% dari Gaji) dan pengecekan hutang aktif.

### 4. Keamanan & Teknologi
* **PWA (Mobile Ready):** Aplikasi dapat diinstal ke *Home Screen* HP, berjalan *full-screen* tanpa address bar browser.
* **Audit Trail:** Mencatat aktivitas sensitif Admin (Edit Gaji, Hapus Data, dll).
* **Force Change Password:** Pegawai baru wajib mengganti password default saat login pertama demi keamanan.

---

## 🛠️ Teknologi yang Digunakan

* **Backend Framework:** Laravel 12
* **Frontend UI:** AdminLTE v4 (Bootstrap 5)
* **Database:** MySQL
* **Mapping Engine:** Leaflet.js + OpenStreetMap
* **Camera API:** Webcam.js
* **PDF Generator:** DomPDF
* **PWA:** Service Worker & Web Manifest


