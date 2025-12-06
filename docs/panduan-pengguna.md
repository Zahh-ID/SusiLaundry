# Panduan Pengguna (User Manual)

Dokumen ini berisi cara menggunakan fitur-fitur utama Susi Laundry untuk **Admin**.

## A. Login ke Sistem
1.  Buka halaman depan, klik tombol **Login** di pojok kanan atas (atau akses `/login`).
2.  Masukkan Email dan Password admin.
3.  Klik "Masuk". Anda akan diarahkan ke Dashboard.

## B. Membuat Pesanan Baru (Walk-in)
Gunakan ini jika ada pelanggan datang membawa cucian.

1.  Di menu samping, klik **"Pesanan"**.
2.  Klik tombol **"+ Pesanan Baru"** di pojok kanan atas.
3.  **Langkah 1: Pilih Layanan**
    *   Pilih "Layanan Satuan" atau "Kiloan".
    *   Pilih Paket (misal: "Cuci Setrika Express").
4.  **Langkah 2: Data Pelanggan**
    *   Jika pelanggan lama: Ketik namanya di kotak pencarian, lalu pilih.
    *   Jika pelanggan baru: Isi Nama, No HP, dan Alamat baru.
5.  **Langkah 3: Detail & Pembayaran**
    *   Masukkan **Berat (Kg)** cucian.
    *   Pilih Metode Pembayaran:
        *   **Tunai**: Jika pelanggan bayar cash sekarang, centang kotak "Pembayaran Diterima?".
        *   **QRIS**: Pilih QRIS jika ingin bayar scan.
6.  Klik **"Buat Pesanan"**.
7.  Selesai! Nota (Invoice) bisa langsung dicetak.

## C. Memproses Cucian (Update Status)
Cucian harus diproses bertahap agar pelanggan bisa memantau.

1.  Buka menu **"Pesanan"**.
2.  Cari pesanan yang mau diproses.
3.  Klik tombol **"Proses Order"** (ikon panah atau gear).
4.  Akan muncul jendela konfirmasi untuk pindah ke tahap selanjutnya.
    *   *Pending -> Processing*: Cucian mulai dicuci.
    *   *Processing -> Ready*: Cucian selesai, siap diambil.
    *   *Ready -> Taken*: Cucian sudah diambil pelanggan.

> **PENTING**: Sistem tidak akan mengizinkan status berubah jadi **Taken (Diambil)** jika status pembayarannya masih **Belum Lunas**.

## D. Mengelola Pembayaran QRIS
Jika pelanggan memilih QRIS tapi belum bayar:

1.  Buka detail pesanan.
2.  Akan muncul tombol **"Cek Status Pembayaran"**.
3.  Jika QRIS kadaluarsa, klik tombol **"Generate QRIS Baru"** untuk membuat kode baru.
4.  Minta pelanggan scan. Jika sukses, status otomatis berubah jadi "Lunas".

## E. Melihat Laporan
1.  Klik menu **"Laporan"**.
2.  Pilih rentang tanggal (misal: 1 Bulan Terakhir).
3.  Klik **"Export Excel"** untuk mengunduh laporan keuangan.
