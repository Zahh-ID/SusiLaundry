# Cara Kerja Website (Konteks: Susi Laundry)

Penjelasan sederhana tentang bagaimana aplikasi ini bekerja, ibarat memesan makanan di restoran.

## 1. Konsep Dasar: Klien & Server
*   **Klien (HP/Laptop Kamu)**: Ini adalah alat yang kamu gunakan untuk membuka website. Tugasnya adalah **Meminta (Request)**.
*   **Server (Komputer Laundry)**: Ini adalah komputer pusat yang menyimpan semua data Susi Laundry. Tugasnya adalah **Melayani (Response)**.

## 2. Perjalanan Satu Klik "Cek Status"
Bayangkan saat pelanggan mengecek status laundry di HP:

1.  **Mencari Alamat**: Saat mengetik `susilaundry.com`, HP bertanya ke internet "Dimana rumah Susi Laundry?" (Ini namanya DNS).
2.  **Mengirim Pesan (Request)**: HP mengirim pesan ke Server Susi Laundry:
    > "Tolong tampilkan status untuk kode pesanan XYZ dong."
3.  **Server Bekerja (Di Dapur)**:
    *   Server menerima pesan.
    *   Server membuka **Database** (Buku Catatan Besar).
    *   Server mencari: "Ada nggak kode XYZ?"
    *   Database menjawab: "Ada! Punya Budi, statusnya sedang dicuci."
4.  **Menyiapkan Jawaban**: Server menyusun tampilan halaman yang rapi berisi tulisan "Sedang Dicuci".
5.  **Mengirim Balasan (Response)**: Server mengirim halaman itu balik ke HP.
6.  **Tampil di Layar**: HP kamu menerima halaman itu dan menampilkannya.

## 3. Update Tanpa Loading (Livewire)
Aplikasi Susi Laundry itu canggih. Kalau kamu perhatikan, saat mengecek status pembayaran, halaman tidak berkedip (reload) tapi statusnya bisa berubah sendiri.

*   Ini karena HP & Server **berbisik-bisik** di belakang layar (Teknik ini namanya AJAX/Livewire).
*   HP bertanya terus setiap 15 detik: "Udah bayar belum pak? Udah bayar belum?"
*   Saat sudah bayar, Server berbisik: "Sudah!".
*   HP langsung ganti tulisan "Belum Lunas" jadi "Lunas" tanpa memuat ulang seluruh halaman.

## 4. Database (Buku Catatan)
Database adalah tempat penyimpanan semua data. Bayangkan file Excel raksasa yang sangat rapi.
*   Tabel **Orders**: Daftar pesanan (Siapa, Paket apa, Berapa kg).
*   Tabel **Customers**: Daftar nomor HP dan alamat pelanggan.
*   Kita menggunakan bahasa **SQL** untuk bicara dengan database.
