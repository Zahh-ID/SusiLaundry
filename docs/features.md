# Fitur Sistem Manajemen Susi Laundry

Dokumen ini menjelaskan kemampuan utama aplikasi Susi Laundry dengan bahasa yang mudah dipahami.

## 1. Halaman Depan (Website Publik)
*   **Daftar Paket Laundry**: Menampilkan pilihan paket cuci yang tersedia (diambil langsung dari database).
*   **Cek Resi / Tracking**: Formulir untuk mengecek status laundry tanpa perlu login. Cukup masukkan kode order.
*   **Informasi & Testimoni**: Bagian untuk menampilkan tanya jawab (FAQ) dan keunggulan laundry.

## 2. Manajemen Pesanan (Khusus Admin)
*   **Input Pesanan Walk-in (Offline)**:
    *   Formulir bertahap yang mudah: Pilih Layanan -> Isi Data Pelanggan -> Konfirmasi.
    *   **Konfirmasi Pembayaran Langsung**: Bisa langsung ditandai "Lunas" jika pelanggan bayar tunai di tempat.
    *   **Pilihan QRIS**: Bisa membuat kode QRIS otomatis jika pelanggan ingin bayar non-tunai.
*   **Daftar Pesanan**:
    *   Bisa cari pesanan berdasarkan Nama atau Kode.
    *   Filter pesanan: Mana yang "Belum Lunas", "Siap Diambil", atau "Selesai".
*   **Alur Kerja Otomatis**:
    *   Status pesanan dikunci langkah-demi-langkah: `Menunggu Konfirmasi` -> `Diproses` -> `Siap Diambil` -> `Diambil` -> `Selesai`.
    *   **Kunci Pengambilan**: Barang **TIDAK BISA** diambil jika statusnya belum "Lunas". Sistem akan menolak otomatis.

## 3. Pelacakan Pelanggan (Tracking)
*   **Status Real-time**: Pelanggan memantau cucian mereka menggunakan Kode Unik (10 digit).
*   **Sistem Pembayaran**:
    *   Jika belum bayar, akan muncul kode QRIS yang bisa discan.
    *   **QRIS Selalu Baru**: Jika kode QRIS kadaluarsa saat halaman dibuka, sistem otomatis membuatkan yang baru.
    *   Tanda "Lunas" akan muncul otomatis setelah pembayaran sukses.
*   **Garis Waktu (Timeline)**: Tampilan visual perjalanan cucian (Diterima -> Dicuci -> Selesai).

## 4. Dashboard Admin
*   **Ringkasan**: Melihat total pendapatan, jumlah cucian aktif, dan cucian selesai dalam satu layar.
*   **Grafik**: Melihat tren penjualan harian/bulanan.

## 5. Fitur Lainnya
*   **Keamanan**: Halaman admin butuh login email & password.
*   **Notifikasi Email**:
    *   Email masuk saat pesanan dibuat (berisi Kode Tracking).
    *   Email update saat cucian selesai.
    *   Email bukti pembayaran (Notanya).
*   **Cetak Nota**: Bisa print nota kecil (thermal) untuk ditempel di bungkusan.
*   **Kelola Paket & Pelanggan**:
    *   Tambah/Hapus jenis paket laundry.
    *   Lihat data pelanggan yang pernah mencuci.
*   **Laporan**: Download data penjualan ke Excel untuk pembukuan.

## 6. Sistem Pembayaran
*   **Tunai (Cash)**: Admin klik tombol konfirmasi manual.
*   **QRIS (Otomatis)**:
    *   Menggunakan Midtrans.
    *   Jika pelanggan bayar lewat HP, status di sistem otomatis berubah jadi "Lunas".
