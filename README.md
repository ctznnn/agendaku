# 📅 Sistem Agenda Kalurahan Trirenggo

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=flat&logo=tailwindcss)
![License](https://img.shields.io/badge/License-MIT-green.svg)

> Sistem manajemen agenda untuk Kalurahan Trirenggo yang memungkinkan admin mengelola agenda dan pegawai, serta pegawai dapat melihat jadwal dan mengkonfirmasi kehadiran.

## ✨ Fitur Unggulan

### 👑 Admin
- ✅ **CRUD Agenda** - Buat, baca, edit, hapus agenda
- ✅ **Filter Agenda** - Berdasarkan tanggal, status, dan pencarian
- ✅ **Share Agenda** - Single share & bulk share ke WhatsApp
- ✅ **CRUD Pegawai** - Kelola data pegawai
- ✅ **Aktif/Nonaktifkan** - Kontrol akses pegawai
- ✅ **Reset Password** - Reset password pegawai

### 👤 Pegawai
- ✅ **Lihat Jadwal** - Melihat agenda yang diikuti
- ✅ **Filter Jadwal** - Berdasarkan tanggal dan status
- ✅ **Konfirmasi Kehadiran** - Hadir / Tidak Hadir

### 🔧 Umum
- ✅ **Login & Logout** - Sistem autentikasi
- ✅ **Edit Profil** - Ubah nama dan unit kerja
- ✅ **Ganti Password** - Keamanan akun
- ✅ **Ganti Email** - Dengan verifikasi email
- ✅ **Dark/Light Mode** - Tampilan siang/malam

## 🚀 Quick Start

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL >= 5.7

### Instalasi

```bash
# Clone repository
git clone https://github.com/ctznnn/agendaku.git
cd agendaku

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Setup database (sesuaikan di .env)
php artisan migrate --seed

# Start server
php artisan serve
