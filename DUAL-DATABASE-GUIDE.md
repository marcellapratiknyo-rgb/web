# Dual Database Architecture - Narayana Karimunjawa

## 📊 Konsep Arsitektur

Website booking **terpisah** dari sistem ADF, tapi **ambil data master** dari sistem.

### Database 1: **adf_narayana_hotel** (Sistem ADF) - READ ONLY
- **Fungsi**: Sumber data master
- **Data yang diambil**: 
  - Room types (tipe kamar)
  - Rooms (nama kamar, nomor kamar)
  - Room prices (harga)
  - Occupancy status
  - Room amenities
- **Akses**: READ ONLY (hanya baca, tidak edit)
- **Koneksi**: `$pdo`

### Database 2: **adf_web_narayana** (Website Booking) - READ WRITE
- **Fungsi**: Simpan transaksi website
- **Data yang disimpan**:
  - Bookings (booking dari customer online)
  - Guests (data tamu dari website)
  - Payments (pembayaran online)
  - Reviews (review customer)
- **Akses**: READ WRITE (baca & tulis)
- **Koneksi**: `$pdo_web`

---

## 🔧 Cara Menggunakan

### 1. Ambil Data dari SISTEM (Room Types, Harga)

```php
<?php
require_once __DIR__ . '/../config/config.php';

// Ambil semua room types dari DATABASE SISTEM
$room_types = dbFetchAll("SELECT * FROM room_types WHERE is_active = 1 ORDER BY base_price ASC");

// Ambil room detail dari DATABASE SISTEM
$room = dbFetch("SELECT * FROM room_types WHERE id = ?", [$room_id]);

// Cek occupancy dari DATABASE SISTEM
$occupied_rooms = dbFetchAll("
    SELECT room_id, guest_name, check_in, check_out 
    FROM bookings 
    WHERE status = 'checked_in'
");

// Ambil harga room
$price = dbFetch("SELECT base_price, weekend_price FROM room_types WHERE id = ?", [$room_type_id]);
?>
```

**Gunakan**: `dbFetch()` dan `dbFetchAll()` untuk query ke database SISTEM.

---

### 2. Simpan Booking Customer ke DATABASE WEBSITE

```php
<?php
require_once __DIR__ . '/../config/config.php';

// Simpan data guest ke DATABASE WEBSITE
$guest_id = dbWebInsert('guests', [
    'full_name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '+6281234567890',
    'id_card_type' => 'KTP',
    'id_card_number' => '1234567890123456'
]);

// Simpan booking ke DATABASE WEBSITE
$booking_id = dbWebInsert('bookings', [
    'guest_id' => $guest_id,
    'room_type_id' => $room_type_id,  // Reference ke sistem
    'check_in' => $check_in_date,
    'check_out' => $check_out_date,
    'num_adults' => 2,
    'num_children' => 1,
    'total_price' => $total_price,
    'booking_source' => 'website',
    'status' => 'pending'
]);

// Simpan payment ke DATABASE WEBSITE
$payment_id = dbWebInsert('payments', [
    'booking_id' => $booking_id,
    'amount' => $payment_amount,
    'payment_method' => 'midtrans',
    'transaction_id' => $transaction_id,
    'status' => 'pending'
]);
?>
```

**Gunakan**: `dbWebInsert()`, `dbWebUpdate()` untuk simpan data ke database WEBSITE.

---

## 📁 Struktur File

### Local Development
```
Database SISTEM:    adf_narayana_hotel
Database WEBSITE:   adf_web_narayana
```

### Production (Hosting)
```
Database SISTEM:    adfb2574_narayana_hotel
Database WEBSITE:   adfb2574_web_narayana
```

---

## ✅ Keuntungan Arsitektur Ini

1. **Data Master Terpusat**: Edit room, harga di sistem ADF → otomatis update di website
2. **Terpisah & Aman**: Booking customer tidak ganggu database sistem
3. **Mudah Maintenance**: 
   - Edit sistem ADF → folder `adf_system`
   - Edit website booking → folder `narayanakarimunjawa`
4. **Skalabilitas**: Website bisa di-deploy ke server terpisah nantinya

---

## 🚨 PENTING!

### ❌ JANGAN:
- Insert/update data ke database SISTEM dari website
- Simpan booking customer di database SISTEM

### ✅ LAKUKAN:
- Ambil data master (room, harga) dari database SISTEM (READ)
- Simpan booking customer di database WEBSITE (WRITE)

---

## 🔍 Contoh Lengkap: Halaman Rooms

```php
<?php
require_once __DIR__ . '/../config/config.php';

// AMBIL ROOM TYPES DARI SISTEM
$room_types = dbFetchAll("
    SELECT id, type_name, description, base_price, max_guests, amenities, image_url
    FROM room_types 
    WHERE is_active = 1 
    ORDER BY base_price ASC
");

foreach ($room_types as $room): ?>
    <div class="room-card">
        <img src="<?= $room['image_url'] ?>" alt="<?= $room['type_name'] ?>">
        <h3><?= $room['type_name'] ?></h3>
        <p><?= $room['description'] ?></p>
        <p class="price"><?= formatCurrency($room['base_price']) ?> / malam</p>
        <p>Max: <?= $room['max_guests'] ?> tamu</p>
        <a href="booking.php?room_type=<?= $room['id'] ?>" class="btn">Book Now</a>
    </div>
<?php endforeach; ?>
```

---

## 🔍 Contoh Lengkap: Proses Booking

```php
<?php
require_once __DIR__ . '/../config/config.php';

// 1. CEK ROOM TERSEDIA DI SISTEM
$room_type = dbFetch("SELECT * FROM room_types WHERE id = ?", [$_POST['room_type_id']]);

if (!$room_type) {
    die('Room type tidak ditemukan');
}

// 2. CEK HARGA DI SISTEM
$price_per_night = $room_type['base_price'];
$nights = (strtotime($_POST['check_out']) - strtotime($_POST['check_in'])) / 86400;
$total_price = $price_per_night * $nights;

// 3. SIMPAN GUEST DI WEBSITE
$guest_id = dbWebInsert('guests', [
    'full_name' => $_POST['full_name'],
    'email' => $_POST['email'],
    'phone' => $_POST['phone']
]);

// 4. SIMPAN BOOKING DI WEBSITE
$booking_id = dbWebInsert('bookings', [
    'guest_id' => $guest_id,
    'room_type_id' => $room_type['id'],
    'check_in' => $_POST['check_in'],
    'check_out' => $_POST['check_out'],
    'num_adults' => $_POST['num_adults'],
    'num_children' => $_POST['num_children'],
    'total_price' => $total_price,
    'status' => 'pending'
]);

// 5. REDIRECT KE PAYMENT
header('Location: payment.php?booking_id=' . $booking_id);
?>
```

---

## 📞 Support

Jika ada masalah dengan dual database:
1. Cek koneksi dengan: `test-setup.php`
2. Pastikan kedua database sudah dibuat
3. Cek username/password database di `config/config.php`

---

**Update terakhir**: 22 Feb 2026
