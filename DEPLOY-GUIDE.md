# 🚀 PANDUAN DEPLOY — narayanakarimunjawa.com

## LANGKAH 1: Login cPanel Hosting

Buka URL cPanel yang diberikan oleh provider hosting kamu.  
Biasanya: `https://narayanakarimunjawa.com:2083` atau dari panel hosting provider.

---

## LANGKAH 2: Buat Database MySQL

1. Di cPanel, buka **"MySQL Databases"**
2. **Create New Database:**
   - Nama database: `narayana_hotel` (akan jadi `cpaneluser_narayana_hotel`)
   - Klik **Create Database**

3. **Create New User:**
   - Username: `narayana_dbuser` (akan jadi `cpaneluser_narayana_dbuser`)
   - Password: Buat password kuat, **CATAT PASSWORD INI!**
   - Klik **Create User**

4. **Add User to Database:**
   - Pilih user yang baru dibuat
   - Pilih database yang baru dibuat
   - Centang **ALL PRIVILEGES**
   - Klik **Make Changes**

> ⚠️ **PENTING:** Catat nama lengkap database & user (termasuk prefix cPanel).
> Contoh: jika cPanel username kamu `naray123`, maka:
> - Database: `naray123_narayana_hotel`
> - User: `naray123_narayana_dbuser`

---

## LANGKAH 3: Import Database

1. Di cPanel, buka **"phpMyAdmin"**
2. Pilih database yang baru dibuat (contoh: `naray123_narayana_hotel`)
3. Klik tab **"Import"**
4. Klik **"Choose File"** → pilih file **`database-export.sql`** dari folder project
5. Klik **"Go"** / **"Import"**
6. Tunggu sampai selesai — akan muncul pesan sukses

---

## LANGKAH 4: Edit Config Sebelum Upload

Buka file **`config/config.php`** dan edit bagian production:

```php
} else {
    // PRODUCTION — narayanakarimunjawa.com
    define('DB_HOST', 'localhost');
    define('DB_USER', 'naray123_narayana_dbuser');  // ← Ganti dengan user lengkap dari cPanel
    define('DB_PASS', 'password_kamu_disini');       // ← Ganti dengan password yang kamu buat
    define('DB_NAME', 'naray123_narayana_hotel');    // ← Ganti dengan nama DB lengkap dari cPanel
    define('DB_PORT', 3306);
}
```

> Ganti `naray123` dengan cPanel username kamu yang sebenarnya.

---

## LANGKAH 5: Upload Files ke Hosting

### Struktur di Hosting:
```
/home/username/
├── config/                     ← DILUAR public_html (aman!)
│   └── config.php
├── logs/                       ← DILUAR public_html (aman!)
└── public_html/                ← INI DOCUMENT ROOT website
    ├── .htaccess               ← RENAME dari .htaccess.production
    ├── index.php
    ├── rooms.php
    ├── booking.php
    ├── contact.php
    ├── confirmation.php
    ├── api/
    │   └── create-booking.php
    ├── assets/
    │   └── css/
    │       └── style.css
    └── includes/
        ├── header.php
        └── footer.php
```

### Cara Upload via cPanel File Manager:

1. Di cPanel, buka **"File Manager"**

2. **Upload folder `config/` ke LUAR public_html:**
   - Navigasi ke `/home/username/` (home directory, BUKAN public_html)
   - Buat folder **`config`** → upload `config.php` ke dalamnya
   - Buat folder **`logs`** (kosong, untuk error log)

3. **Upload isi folder `public/` ke `public_html/`:**
   - Navigasi ke `/home/username/public_html/`
   - **Hapus file default** (index.html bawaan hosting)
   - Upload semua isi folder `public/`:
     - `index.php`
     - `rooms.php`
     - `booking.php`
     - `contact.php`
     - `confirmation.php`
   - Upload folder `api/` (dengan isinya)
   - Upload folder `assets/` (dengan isinya)
   - Upload folder `includes/` (dengan isinya)

4. **Setup .htaccess:**
   - Upload file `.htaccess.production` ke `public_html/`
   - **RENAME** dari `.htaccess.production` menjadi `.htaccess`
   - (Atau hapus `.htaccess` lama jika ada, lalu rename)

### Alternatif: Upload via FTP (FileZilla)
1. Download FileZilla: https://filezilla-project.org/
2. Di cPanel → **"FTP Accounts"** → buat atau gunakan akun FTP
3. Connect ke server
4. Upload sesuai struktur di atas

---

## LANGKAH 6: Fix Path config.php

Karena `config/` ada di luar `public_html/`, path include di setiap PHP file sudah benar:

```php
require_once dirname(__DIR__) . '/config/config.php';
```

- Dari `public_html/index.php` → `dirname(__DIR__)` = `/home/username/` → `/home/username/config/config.php` ✅
- Dari `public_html/api/create-booking.php` → `dirname(__DIR__, 2)` = `/home/username/` → ✅

> Ini sudah benar karena `public_html` menggantikan folder `public` dan `config` ada satu level di atasnya.

---

## LANGKAH 7: Set File Permissions

Di cPanel File Manager, set permissions:

| File/Folder | Permission |
|---|---|
| `config/config.php` | **644** |
| `config/` folder | **755** |
| `logs/` folder | **755** |
| `public_html/` semua file | **644** |
| `public_html/` semua folder | **755** |
| `.htaccess` | **644** |

---

## LANGKAH 8: Test Website

Buka browser dan test:

1. ✅ **Homepage:** `https://narayanakarimunjawa.com`
2. ✅ **Rooms:** `https://narayanakarimunjawa.com/rooms`
3. ✅ **Booking:** `https://narayanakarimunjawa.com/booking`
4. ✅ **Contact:** `https://narayanakarimunjawa.com/contact`
5. ✅ **Test booking flow** — cari kamar → pilih → isi form → confirm

---

## LANGKAH 9: Setup SSL (HTTPS)

Biasanya hosting sudah include SSL gratis (Let's Encrypt):

1. Di cPanel → **"SSL/TLS"** atau **"Let's Encrypt"**
2. Pilih domain `narayanakarimunjawa.com`
3. Generate/Install SSL Certificate
4. Tunggu beberapa menit

> `.htaccess` sudah otomatis redirect HTTP → HTTPS.

---

## TROUBLESHOOTING

### ❌ Error 500 (Internal Server Error)
- Cek `logs/error.log` atau cPanel → **"Error Log"**
- Biasanya salah path config atau database credentials

### ❌ Error "Database Connection Failed"
- Pastikan nama database, username, dan password di `config.php` sudah benar
- Pastikan user sudah di-add ke database dengan ALL PRIVILEGES

### ❌ Halaman Blank/404
- Pastikan `.htaccess` sudah di-rename dari `.htaccess.production`
- Pastikan mod_rewrite aktif (biasanya sudah di shared hosting)
- Cek apakah file `.htaccess` visible di File Manager (aktifkan "Show Hidden Files")

### ❌ CSS/Style tidak muncul
- Pastikan folder `assets/css/style.css` sudah terupload
- Cek browser console (F12) untuk error path

### ❌ Booking tidak jalan
- Pastikan file `api/create-booking.php` sudah ada
- Cek path database — tabel `bookings`, `guests`, `rooms` harus ada

---

## 📋 CHECKLIST SEBELUM GO LIVE

- [ ] Database dibuat di cPanel
- [ ] Database di-import dari `database-export.sql`
- [ ] `config.php` sudah diupdate dengan credentials hosting
- [ ] Semua file terupload sesuai struktur
- [ ] `.htaccess.production` di-rename jadi `.htaccess`
- [ ] SSL/HTTPS aktif
- [ ] Test semua halaman
- [ ] Test booking flow lengkap
- [ ] Cek responsive di HP

---

## 📁 FILES YANG PERLU DIUPLOAD

```
Dari project lokal          →  Ke hosting
─────────────────────────────────────────────
config/config.php           →  /home/user/config/config.php
(buat folder logs kosong)   →  /home/user/logs/
database-export.sql         →  Import via phpMyAdmin
public/index.php            →  public_html/index.php
public/rooms.php            →  public_html/rooms.php
public/booking.php          →  public_html/booking.php
public/contact.php          →  public_html/contact.php
public/confirmation.php     →  public_html/confirmation.php
public/api/*                →  public_html/api/
public/assets/*             →  public_html/assets/
public/includes/*           →  public_html/includes/
public/.htaccess.production →  public_html/.htaccess (RENAME!)
```
