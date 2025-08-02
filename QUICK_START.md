# ⚡ Quick Start - Sistem Koperasi Arkebono

### 1. **Prerequisites** (Pastikan sudah terinstall)
- ✅ PHP 8.1+
- ✅ MySQL 8.0+
- ✅ Composer
- ✅ Laragon/XAMPP (recommended)

### 2. **Setup Database**
```bash
# Buka phpMyAdmin: http://localhost/phpmyadmin
# Buat database: ar kebono_db
# Import file: database.sql
```

### 3. **Setup Project**
```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate key
php artisan key:generate
```

### 4. **Configure Database**
Edit file `.env`:
```env
DB_DATABASE=ar kebono_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. **Run Application**
```bash
php artisan serve
```

### 6. **Access Application**
🌐 Buka browser: **http://localhost:8000**

## 🎯 Test Fitur

### ✅ **Master Item**
- Klik menu "Master Item"
- Test tambah item baru
- Test edit item
- Test hapus item

### ✅ **Master Karyawan**
- Klik menu "Master Karyawan"
- Test tambah karyawan baru
- Test edit karyawan
- Test hapus karyawan

### ✅ **Transaksi**
- Klik menu "Transaksi"
- Test tambah transaksi baru
- Test edit transaksi

