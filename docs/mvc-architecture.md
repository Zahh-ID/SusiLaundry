# Arsitektur Aplikasi (MVC)

Aplikasi Susi Laundry dibangun menggunakan pola **MVC (Model-View-Controller)**. Agar mudah, ayo bayangkan sebuah **Restoran**.

## 1. Model (M) → Koki & Bahan Makanan
Model adalah bagian yang mengurus **Data**. Dia yang tahu segala aturan tentang data.
*   **Lokasi**: `app/Models/`
*   **Contoh**: `Order.php` (Pesanan), `Package.php` (Menu Paket).
*   **Tugas**:
    *   Berbicara dengan Database (Gudang Bahan).
    *   Contoh Logika: "Satu pesanan punya banyak riwayat pembayaran."

## 2. View (V) → Piring & Tampilan Makanan
View adalah apa yang **dilihat oleh pengguna** di layar.
*   **Lokasi**: `resources/views/`
*   **Contoh**: Halaman Dashboard, Halaman Tracking.
*   **Tugas**:
    *   Menata tulisan, warna, dan tombol agar cantik.
    *   Tidak boleh mikir berat-berat, cuma menampilkan apa yang dikasih.

## 3. Controller (C) → Pelayan (Waiter)
Controller adalah perantara. Dia menerima pesanan pelanggan, menyuruh Koki masak, lalu mengantar makanan ke meja.
*   **Lokasi**: `app/Livewire/`
*   **Contoh**: `TrackOrder.php`, `Create.php`.
*   **Tugas**:
    *   Menerima input (Misal: User klik tombol "Simpan").
    *   Memanggil Model: "Eh Model, simpan data ini ke database ya!"
    *   Memilih View: "Oke data sudah simpan, sekarang tampilkan halaman 'Sukses' ke user."

---

## Contoh Alur: Halaman Tracking

1.  **Kamu Datang** (Request): Membuka `/tracking`.
2.  **Pelayan Menyambut** (Controller - `TrackOrder.php`):
    *   Dia nanya: "Mana kode resinya?"
3.  **Koki Mengecek** (Model - `Order.php`):
    *   Dia ngecek ke Gudang (Database).
    *   "Ini datanya ketemu! Statusnya Pembayaran Pending."
4.  **Penyajian** (View - `track-order.blade.php`):
    *   Data tadi ditaruh di piring cantik (Halaman Web).
    *   Kalau status pending, otomatis tampilin gambar QRIS.
5.  **Dihidangkan** (Response): Kamu melihat halaman tracking di HP.
