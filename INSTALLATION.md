# Installation Guide

Panduan lengkap untuk menginstall dan menjalankan Student Attendance System di environment lokal Anda.

## Prerequisites (Yang Harus Diinstall Dulu)

Sebelum clone project, pastikan sudah install:

1. **PHP 8.1 atau lebih tinggi**
   - Download: https://www.php.net/downloads
   - Cek versi: `php -v`

2. **Composer** (PHP Dependency Manager)
   - Download: https://getcomposer.org/download/
   - Cek versi: `composer -v`

3. **MySQL/MariaDB** (Database)
   - Download MySQL: https://dev.mysql.com/downloads/
   - Atau gunakan XAMPP/Laragon/Herd yang sudah include MySQL

4. **Git** (Version Control)
   - Download: https://git-scm.com/downloads
   - Cek versi: `git --version`

5. **Node.js & NPM** (untuk compile frontend assets)
   - Download: https://nodejs.org/
   - Cek versi: `node -v` dan `npm -v`

## Langkah-langkah Installation

### 1. Clone Repository

```bash
git clone https://github.com/rifareza09/Qr_attendance_students.git
cd Qr_attendance_students
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies (untuk Vite/Tailwind)

```bash
npm install
```

### 4. Setup Environment File

Copy file `.env.example` menjadi `.env`:

**Windows (PowerShell):**
```powershell
Copy-Item .env.example .env
```

**Linux/Mac:**
```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Generate Encryption Key

Generate 64-character encryption key:

**Windows (PowerShell):**
```powershell
php -r "echo bin2hex(random_bytes(32));"
```

**Linux/Mac:**
```bash
php -r "echo bin2hex(random_bytes(32));"
```

Copy hasil output dan paste ke `.env`:
```env
ENCRYPTION_KEY=hasil_generate_tadi
```

### 7. Setup Database

**Option A: Gunakan Database Online (Filess.io - Gratis)**
1. Daftar di https://filess.io
2. Create database MySQL
3. Copy credentials ke `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=your-host.filess.io
DB_PORT=your-port
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

**Option B: Gunakan MySQL Lokal**
1. Create database baru:
```sql
CREATE DATABASE attendance_system;
```
2. Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_system
DB_USERNAME=root
DB_PASSWORD=your-password
```

### 8. Konfigurasi Environment

Edit file `.env` dan sesuaikan:

```env
APP_NAME="Student Attendance System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# WiFi Configuration (opsional untuk production)
DEFAULT_VALID_WIFI=YourCampusWiFiName
ENABLE_WIFI_CHECK=false

# Testing Mode (untuk development)
TESTING_MODE=true

# Two-Factor Authentication
ENABLE_2FA=true

# QR Code Settings
QR_CODE_EXPIRY_MINUTES=30

# Session & Cache (gunakan file untuk mudahnya)
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 9. Run Database Migrations

```bash
php artisan migrate
```

### 10. Run Database Seeders (Create Admin & Default Settings)

```bash
php artisan db:seed
```

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`
- **PENTING:** Ganti password setelah login pertama kali!

### 11. Build Frontend Assets

**Development:**
```bash
npm run dev
```

**Production (build sekali):**
```bash
npm run build
```

### 12. Run Application

**Option A: Laravel Built-in Server**
```bash
php artisan serve
```
Akses: http://localhost:8000

**Option B: Laravel Herd (Windows/Mac)**
Jika sudah install Herd, langsung akses:
```
http://nama-folder.test
```

**Option C: XAMPP/Laragon**
Letakkan project di folder `htdocs` atau `www`, lalu akses:
```
http://localhost/Qr_attendance_students/public
```

## Testing

### Testing Mode

Untuk testing di local dengan multiple device simulation:

1. Set di `.env`:
```env
TESTING_MODE=true
```

2. Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
```

3. Sekarang setiap request akan dapat random IP, jadi bisa register multiple student dari 1 device.

### Production Mode

Untuk deployment ke production:

1. Set di `.env`:
```env
APP_ENV=production
APP_DEBUG=false
TESTING_MODE=false
ENABLE_WIFI_CHECK=true
DEFAULT_VALID_WIFI=NamaWiFiKampusAsli
```

2. Optimize aplikasi:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
npm run build
```

## Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "Permission denied" (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error: "Vite manifest not found"
```bash
npm run build
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"
- Pastikan MySQL sudah running
- Cek credentials di `.env`
- Pastikan port database benar

### Error: "Encryption key is not set"
```bash
php artisan key:generate
```
Dan pastikan `ENCRYPTION_KEY` sudah diisi di `.env`

## Required PHP Extensions

Pastikan extension PHP ini aktif (cek di `php.ini`):

- ✅ OpenSSL
- ✅ PDO
- ✅ Mbstring
- ✅ Tokenizer
- ✅ XML
- ✅ Ctype
- ✅ JSON
- ✅ BCMath
- ✅ Fileinfo
- ✅ GD (untuk QR Code SVG)

Cek extension aktif:
```bash
php -m
```

## Default Credentials After Seeding

**Admin:**
- URL: http://your-domain/admin/login
- Username: `admin`
- Password: `admin123`
- 2FA: Enabled (scan QR on first login)

**Student:**
- Register via homepage
- Credentials akan di-generate otomatis

## Support

Jika ada masalah:
1. Clear cache: `php artisan cache:clear`
2. Clear config: `php artisan config:clear`
3. Clear route: `php artisan route:clear`
4. Clear view: `php artisan view:clear`
5. Restart server

## License

Open source - MIT License
