# Panduan Struktur Folder Proyek

Untuk kamu yang ingin mengutak-atik kodenya, ini peta lokasi file-file penting di Susi Laundry.

## Folder Utama
*   `app/`: **Dapur Utama**. Semua logika pemrograman (PHP) ada di sini.
*   `resources/`: **Bahan Mentah Tampilan**. File HTML (Blade) dan CSS ada di sini.
*   `database/`: **Gudang Data**. File untuk mengatur tabel database.
*   `routes/`: **Gerbang Pintu**. Mengatur alamat website (misal: `/admin` arahnya kemana).
*   `public/`: **Etalase**. File yang bisa diakses publik seperti gambar logo `images/`.

## Di mana saya harus mengedit?

### 1. Kalau mau ubah Tampilan (HTML/Warna)
Buka folder: `resources/views/livewire/`
*   `admin/order/create.blade.php`: Tampilan form input pesanan.
*   `track-order.blade.php`: Tampilan halaman tracking pelanggan.
*   `layouts/admin.blade.php`: Tampilan kerangka admin (menu samping).

### 2. Kalau mau ubah Logika (Cara kerja)
Buka folder: `app/Livewire/`
*   `Admin/Order/Create.php`: Logika saat mau simpan pesanan baru.
*   `TrackOrder.php`: Logika saat pelanggan cek resi (termasuk generate QRIS otomatis).

### 3. Kalau mau ubah Database (Tabel)
Buka folder: `app/Models/` atau `database/migrations/`
*   Di sini tempat mengatur kolom-kolom tabel seperti `Order` atau `Customer`.

## Contoh Kasus
**"Saya mau ganti warna tombol 'Simpan' jadi Merah."**
> Cari filenya di `resources/views/...`. Ubah kode `bg-primary` jadi `bg-red-500`.

**"Saya mau pesanan otomatis lunas kalau beratnya 0 kg."**
> Cari filenya di `app/Livewire/...`. Tambahkan logika `if ($berat == 0) ...` di situ.
