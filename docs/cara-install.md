# Panduan Instalasi (Cara Install)

Panduan ini untuk kamu yang ingin menjalankan aplikasi Susi Laundry di komputer sendiri (Localhost).

## Persyaratam Sistem (Syarat Minimal)
Sebelum mulai, pastikan laptop kamu sudah terinstall:
1.  **PHP** (Minimal versi 8.1 atau 8.2).
2.  **Composer** (Manajer paket PHP).
3.  **Node.js & NPM** (Untuk mengurus tampilan/CSS).
4.  **Database MySQL** (Bisa pakai XAMPP atau Laragon).
5.  **Git** (Untuk mengambil kode).

---

## Langkah-Langkah Instalasi

### 1. Ambil Kode Aplikasi (Clone)
Buka terminal (CMD/Terminal) dan ketik:
```bash
git clone https://github.com/Zahh-ID/SusiLaundry.git
cd SusiLaundry
```

### 2. Install "Onderdil" PHP (Composer)
Kita perlu mengunduh semua library PHP yang diperlukan framework Laravel.
```bash
composer install
```
*Tunggu sampai selesai. Ini butuh internet.*

### 3. Install "Onderdil" Tampilan (NPM)
Kita perlu mengunduh library untuk CSS dan Javascript.
```bash
npm install
npm run build
```

### 4. Siapkan File Pengaturan (.env)
Copy file contoh pengaturan menjadi file pengaturan asli.
```bash
cp .env.example .env
```
Lalu buat "Kunci Rahasia" aplikasi:
```bash
php artisan key:generate
```

### 5. Siapkan Database
1.  Buka aplikasi Database kamu (misal phpMyAdmin di http://localhost/phpmyadmin).
2.  Buat database baru dengan nama: `susi_laundry`.
3.  Buka file `.env` di teks editor, cari bagian ini dan sesuaikan:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=susi_laundry
    DB_USERNAME=root
    DB_PASSWORD=
    ```

### 6. Isi Database (Migrate & Seed)
Masukkan tabel-tabel dan data contoh (User admin default) ke dalam database.
```bash
php artisan migrate --seed
```

### 7. Jalankan Aplikasi!
Terakhir, nyalakan servernya:
```bash
php artisan serve
```
Buka browser dan akses: `http://localhost:8000`

---

## Akun Login (Default)
Jika kamu menjalankan `--seed` di langkah 6, kamu bisa login dengan:
*   **Email**: `admin@susilaundry.com`
*   **Password**: `password`
