# 🚀 SETUP CEPAT - Student Attendance System

## Step 1: Copy Environment File
```bash
cp .env.example .env
```

## Step 2: Generate Keys
```bash
# Generate app key
php artisan key:generate

# Generate encryption key (run in tinker)
php artisan tinker
>>> echo bin2hex(random_bytes(32));
>>> exit
# Copy output ke .env sebagai ENCRYPTION_KEY
```

## Step 3: Run Migrations
```bash
php artisan migrate
php artisan db:seed
```

## Step 4: Setup Storage
```bash
php artisan storage:link
npm install
npm run build
```

## Step 5: Start Server
```bash
# Herd (auto): http://attendance-student-vivi.test
# Manual:
php artisan serve
```

## Default Login
- **URL**: `/admin/login`
- **Username**: `admin`
- **Password**: `admin123`

⚠️ **GANTI PASSWORD SETELAH LOGIN!**

## Konfigurasi WiFi
Update di `.env`:
```env
DEFAULT_VALID_WIFI=YourWiFiName
ENABLE_WIFI_CHECK=true
```

## Troubleshooting

### QR Code Error
```bash
chmod -R 755 storage/app/qrcodes
```

### Migration Error
```bash
php artisan migrate:fresh --seed
```

### Packages Missing
```bash
composer install --no-interaction
```

---

📖 **Dokumentasi Lengkap**: Lihat [README.md](README.md)
