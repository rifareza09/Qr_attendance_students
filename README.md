# 📚 Student Attendance System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistem absensi mahasiswa berbasis QR Code dengan fitur keamanan tinggi, validasi jaringan WiFi, dan Two-Factor Authentication untuk admin.

---

## ✨ Fitur Utama

### 🔐 Keamanan & Validasi
- ✅ **Network-based Security**: Validasi WiFi kampus wajib
- ✅ **IP Address Tracking**: Pelacakan IP untuk setiap absensi
- ✅ **QR Code Expiry**: QR code dengan masa berlaku yang bisa dikonfigurasi
- ✅ **One-Time QR Usage**: QR code tidak bisa dipakai berulang di hari yang sama
- ✅ **Password Encryption**: Bcrypt hashing untuk password
- ✅ **Data Encryption**: AES-256-CBC untuk data sensitif
- ✅ **Two-Factor Authentication**: Google Authenticator untuk admin
- ✅ **Activity Logging**: Audit trail lengkap semua aktivitas

### 👨‍🎓 Fitur Mahasiswa
- ✅ **Auto-Registration**: Input nama → sistem generate username & password otomatis
- ✅ **QR Code Generation**: QR code unik per mahasiswa
- ✅ **One-Click Attendance**: Mark absensi dengan satu klik
- ✅ **Personal Dashboard**: Lihat history dan statistik absensi pribadi
- ✅ **Real-time Validation**: Validasi WiFi dan IP address otomatis

### 👨‍💼 Fitur Admin
- ✅ **Comprehensive Dashboard**: 4 tab manajemen (Attendance, Students, Settings, Logs)
- ✅ **Student Management**: CRUD mahasiswa lengkap dengan view credentials
- ✅ **Attendance Control**: Validasi & invalidasi absensi
- ✅ **Export Data**: Export dalam format CSV & JSON
- ✅ **System Settings**: Konfigurasi WiFi SSID, QR expiry, dll
- ✅ **Activity Monitoring**: Real-time activity logs dengan filter

---

## 📋 Requirements

- **PHP** >= 8.2
- **Composer** (latest)
- **Node.js** & NPM (latest LTS)
- **MySQL/MariaDB** (atau database lain yang didukung Laravel)
- **Laravel Herd** (recommended) atau PHP built-in server

---

## 🚀 Quick Start

**Untuk instalasi lengkap step-by-step, lihat [INSTALLATION.md](INSTALLATION.md)**

### Quick Installation

```bash
# 1. Clone repository
git clone https://github.com/rifareza09/Qr_attendance_students.git
cd Qr_attendance_students

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Generate encryption key
php -r "echo bin2hex(random_bytes(32));"
# Copy output ke ENCRYPTION_KEY di .env

# 5. Configure database di .env
# Update DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 6. Run migrations & seeders
php artisan migrate --seed

# 7. Run application
php artisan serve
# Akses: http://localhost:8000
```

**Default Admin:** `admin` / `admin123`

---

## 📖 Documentation

- **[Installation Guide](INSTALLATION.md)** - Panduan instalasi lengkap
- **[Setup Guide](SETUP.md)** - Konfigurasi dan deployment

### 1️⃣ Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 2️⃣ Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

**Generate Encryption Key:**
```bash
php artisan tinker
# Di dalam tinker:
echo bin2hex(random_bytes(32));
# Copy hasilnya dan paste ke .env sebagai ENCRYPTION_KEY
exit
```

### 3️⃣ Database Configuration

Database sudah dikonfigurasi untuk Filess.io MySQL di `.env.example`. Pastikan konfigurasi berikut ada di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=q5ers6.h.filess.io
DB_PORT=61031
DB_DATABASE=attendance_system_fallbirth
DB_USERNAME=attendance_system_fallbirth
DB_PASSWORD=fcde6e9aae08d3cb490fffea5b261c9d6a91c08d
```

### 4️⃣ Run Migrations & Seed Database

```bash
# Run migrations
php artisan migrate

# Seed database dengan admin & settings default
php artisan db:seed
```

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **PENTING**: Ganti password ini setelah login pertama kali!

### 5️⃣ Build Assets & Create Storage Link

```bash
# Build Tailwind CSS
npm run build

# Create storage symbolic link
php artisan storage:link
```

### 6️⃣ Start Server

**Jika menggunakan Laravel Herd:**
- Otomatis tersedia di: `http://attendance-student-vivi.test`

**Atau gunakan PHP built-in server:**
```bash
php artisan serve
# Akses di http://localhost:8000
```

---

## 📖 Dokumentasi Lengkap

| Dokumen | Deskripsi |
|---------|-----------|
| [📦 INSTALLATION.md](INSTALLATION.md) | Panduan instalasi lengkap step-by-step |
| [⚡ QUICKSTART.md](QUICKSTART.md) | Panduan cepat memulai sistem |
| [🔧 API.md](API.md) | Dokumentasi API endpoints |
| [🚀 DEPLOYMENT.md](DEPLOYMENT.md) | Panduan deploy ke production |
| [💻 COMMANDS.md](COMMANDS.md) | Daftar Artisan commands tersedia |
| [📝 CHANGELOG.md](CHANGELOG.md) | Riwayat perubahan versi |
| [📊 PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) | Ringkasan lengkap project |

---

## 🗂️ Struktur Project

```
app/
├── Console/Commands/          # Artisan commands
│   └── CleanupExpiredQrCodes.php
├── Http/
│   ├── Controllers/          # Controllers
│   │   ├── AdminController.php
│   │   ├── AttendanceController.php
│   │   ├── SettingController.php
│   │   └── StudentController.php
│   └── Middleware/           # Custom middleware
│       ├── CheckWifiNetwork.php
│       ├── TwoFactorAuth.php
│       └── ValidateIP.php
├── Models/                   # Eloquent models
│   ├── Attendance.php
│   ├── QrCode.php
│   ├── Setting.php
│   ├── Student.php
│   └── User.php
├── Services/                 # Business logic
│   ├── AttendanceService.php
│   ├── EncryptionService.php
│   ├── NetworkValidator.php
│   └── QrCodeService.php
└── Helpers/                  # Helper functions
    └── helpers.php

database/
├── migrations/               # Database migrations
└── seeders/                  # Database seeders

resources/views/              # Blade templates
├── layouts/
├── admin/
└── student/

storage/app/                  # File storage
├── qrcodes/                  # Generated QR codes
└── exports/                  # Exported data
```

---

## 🔑 Access URLs

| Portal | URL | Credentials |
|--------|-----|-------------|
| **Student Portal** | `/` | Auto-register dengan nama |
| **Admin Login** | `/admin/login` | Username: `admin`<br>Password: `admin123` |
| **Admin Dashboard** | `/admin/dashboard` | Setelah login & 2FA |

---

## ⚙️ Konfigurasi Sistem

### WiFi Settings

Ubah SSID WiFi yang valid melalui:

**Cara 1: File `.env`**
```env
DEFAULT_VALID_WIFI=NamaWiFiKampus
ENABLE_WIFI_CHECK=true
```

**Cara 2: Admin Dashboard**
1. Login sebagai admin
2. Buka tab **Settings**
3. Update "Valid WiFi SSID"
4. Klik "Save Settings"

### QR Code Settings

```env
QR_CODE_SIZE=300              # Ukuran QR code (pixels)
QR_CODE_FORMAT=png            # Format file
QR_EXPIRY_MINUTES=30          # Masa berlaku (menit)
```

### Two-Factor Authentication

```env
ENABLE_2FA=true               # Enable/disable 2FA globally
GOOGLE2FA_WINDOW=1            # Time window untuk validasi
```

---

## 📱 Cara Penggunaan

### Untuk Mahasiswa

1. **Registrasi**
   - Buka homepage sistem
   - Masukkan nama lengkap
   - Klik "Register Now"
   - **SIMPAN** username & password yang muncul (hanya ditampilkan sekali!)

2. **Mark Attendance**
   - Kembali ke homepage
   - Sistem akan detect IP Anda
   - Klik "Mark Attendance Now"
   - Sistem validasi WiFi & IP
   - QR code di-generate otomatis
   - Absensi berhasil!

3. **View Dashboard**
   - Klik "View My Dashboard"
   - Lihat statistik: Total, Bulan Ini, Minggu Ini
   - Lihat history absensi lengkap

### Untuk Admin

1. **Login**
   - Buka `/admin/login`
   - Masukkan username & password
   - Scan QR code dengan Google Authenticator (jika 2FA enabled)
   - Masukkan 6-digit code

2. **Dashboard**
   - **Tab Attendance**: Lihat absensi hari ini, invalidate jika perlu
   - **Tab Students**: Manajemen mahasiswa, view credentials, edit, deactivate
   - **Tab Settings**: Update WiFi SSID, QR expiry time
   - **Tab Logs**: Monitor semua aktivitas sistem

3. **Export Data**
   - Klik tombol "Export CSV" di tab Attendance
   - Atau akses `/admin/export?format=csv` atau `format=json`
   - Filter by date range (optional)

---

## 🛠️ Artisan Commands

```bash
# Cleanup expired QR codes (manual)
php artisan qrcode:cleanup

# Run scheduled tasks (auto cleanup)
php artisan schedule:run

# Seed database ulang
php artisan db:seed

# Clear all cache
php artisan optimize:clear

# Generate encryption key helper
php artisan tinker
>>> echo bin2hex(random_bytes(32));
```

**Scheduled Tasks:**
QR code cleanup otomatis berjalan setiap jam via Laravel scheduler.

---

## 🔒 Fitur Keamanan

| Fitur | Implementasi |
|-------|--------------|
| **Password Hashing** | Bcrypt (Laravel default) |
| **Data Encryption** | AES-256-CBC via OpenSSL |
| **QR Code Security** | SHA-256 hashing |
| **IP Validation** | Filter & tracking setiap request |
| **WiFi Validation** | SSID checking via system commands |
| **CSRF Protection** | Laravel built-in |
| **2FA** | Google Authenticator (TOTP) |
| **Activity Logging** | Spatie Activity Log |
| **Rate Limiting** | Configurable untuk login & attendance |

---

## 📦 Package Dependencies

### PHP (Composer)
```json
{
  "simplesoftwareio/simple-qrcode": "^4.2",
  "pragmarx/google2fa-laravel": "^2.0",
  "spatie/laravel-activitylog": "^4.0",
  "maatwebsite/excel": "^3.1"
}
```

### JavaScript (NPM)
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Build tool

---

## 🐛 Troubleshooting

### QR Code tidak muncul
```bash
php artisan storage:link
chmod -R 755 storage/app/qrcodes  # Linux/Mac
# Windows: Set permissions via Explorer
```

### WiFi validation selalu gagal
```bash
# Temporary disable
ENABLE_WIFI_CHECK=false

# Or update valid SSID di Admin Dashboard
```

### Migration error
```bash
# Fresh migration
php artisan migrate:fresh --seed

# Drop all tables first
php artisan db:wipe
php artisan migrate
php artisan db:seed
```

### Composer error
```bash
composer update --no-scripts
composer dump-autoload
```

### 2FA tidak bekerja
```bash
# Disable 2FA temporarily
ENABLE_2FA=false

# Or reset user's 2FA secret di database
```

---

## 🧪 Testing (Optional)

```bash
# Run all tests
php artisan test

# Specific test
php artisan test --filter=AttendanceTest

# With coverage
php artisan test --coverage
```

---

## 🤝 Contributing

Kontribusi sangat diterima! Silakan:

1. Fork repository
2. Create feature branch: `git checkout -b feature/AmazingFeature`
3. Commit changes: `git commit -m 'Add AmazingFeature'`
4. Push to branch: `git push origin feature/AmazingFeature`
5. Open Pull Request

---

## 📄 License

Project ini dilisensikan under [MIT License](LICENSE).

---

## 🙏 Credits & Acknowledgments

- **Laravel Framework** - PHP Framework
- **Tailwind CSS** - CSS Framework
- **SimpleSoftwareIO** - QR Code Library
- **Spatie** - Activity Log Package
- **PragmaRX** - Google2FA Package
- **Laravel Community** - Amazing ecosystem

---

## 📞 Support & Contact

- **Issues**: Create an issue di GitHub repository
- **Email**: admin@attendance.test
- **Documentation**: Lihat folder dokumentasi

---

## 🔄 Maintenance

### Regular Tasks
```bash
# Harian: Auto cleanup via scheduler
php artisan schedule:run

# Mingguan: Check logs
tail -f storage/logs/laravel.log

# Bulanan: Update dependencies
composer update
npm update
```

### Monitoring Checklist
- ✅ Check activity logs untuk aktivitas mencurigakan
- ✅ Monitor storage space untuk QR codes
- ✅ Review invalid attendances
- ✅ Update WiFi SSID jika berubah
- ✅ Backup database secara berkala

---

**Version**: 1.0.0  
**Last Updated**: November 2025  
**Laravel**: 11.x  
**PHP**: 8.2+  

Made with ❤️ using Laravel & Tailwind CSS
