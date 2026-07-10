# NexTech Store

NexTech Store adalah aplikasi web e-commerce sederhana berbasis PHP murni (Vanilla PHP) menggunakan pola arsitektur **MVC (Model-View-Controller)**. Proyek ini dibangun dengan tujuan pembelajaran pengembangan web terstruktur.

## Fitur Utama

## Customer (Pelanggan)
- **Katalog Produk:** Melihat semua daftar produk elektronik yang tersedia.
- **Keranjang Belanja:** Menambah, mengubah kuantitas, dan menghapus produk dari keranjang.
- **Checkout:** Mengisi formulir pengiriman dan menyelesaikan pesanan.
- **Riwayat Pesanan:** Melacak pesanan (Pending, Diproses, Selesai, Dibatalkan).
- **Profil:** Melihat detail akun dan mengubah password.

## Administrator
- **Dashboard:** Statistik toko (Total Produk, Pengguna, Pendapatan, Grafik Penjualan).
- **Kelola Produk:** Operasi CRUD (Tambah, Edit, Hapus) produk, termasuk unggah gambar.
- **Kelola Pesanan:** Mengubah status pesanan pelanggan.
- **Kelola Pengguna:** Melihat daftar pengguna dan mengubah hak akses (Admin/Customer).

---

## Panduan Instalasi (Local Development)

### Persyaratan Sistem
- XAMPP / MAMP / WAMP (PHP >= 8.0)
- MySQL / MariaDB Database

### Langkah-langkah:

1. Clone Repository
   Buka terminal, arahkan ke folder `htdocs` (jika menggunakan XAMPP), lalu jalankan:
   ```bash
   git clone https://github.com/RizalDwi86/nextech-store.git
   cd nextech-store
   ```

2. Setup Database
   - Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`).
   - Buat database baru bernama `nextech_store`.
   - Import file SQL yang telah disediakan: Klik tab *Import* lalu pilih file `database/nextech_store.sql`.

3. Konfigurasi Database (Opsional)
   Secara default, aplikasi akan mencoba login ke MySQL dengan kredensial:
   - Host: `localhost`
   - User: `root`
   - Password: `(kosong)`
   - DB Name: `nextech_store`
   
   *Jika pengaturan XAMPP Anda berbeda, ubah file `app/core/Database.php`.*

4. **Jalankan Aplikasi**
   Buka browser dan akses:
   ```
   http://localhost/nextech-store
   ```

---

## Akun Uji Coba (Testing)

Saat Anda mengimpor file `nextech_store.sql`, database secara otomatis akan memiliki dua akun uji coba yang bisa langsung digunakan:

### Akun Administrator
- **Email:** `admin@nextech.com`
- **Password:** `admin123`

---

## Struktur Folder (MVC)

```text
nextech-store/
├── app/
│   ├── controllers/   # Mengatur logika bisnis dan alur data
│   ├── core/          # File inti (App, Controller, Database, Model)
│   ├── models/        # Mengelola kueri database (CRUD)
│   └── views/         # Antarmuka (HTML/PHP + Bootstrap)
├── config/            # Folder konfigurasi tambahan (jika ada)
├── database/          # File dump SQL (.sql)
├── public/            # File aset publik (gambar, css, js)
│   └── uploads/       # Tempat penyimpanan gambar produk
├── .gitignore
├── index.php          # Entry point (Halaman Login)
└── register.php       # Halaman Registrasi
```
