# Teknologi yang Digunakan

Berikut adalah daftar "alat tukang" (Tech Stack) yang digunakan untuk membangun Susi Laundry.

## Inti (Backend)
*   **Bahasa**: PHP 8.2+
*   **Framework**: Laravel 11
    *   Kerangka kerja utama yang mengatur alur aplikasi.

## Tampilan (Frontend)
*   **Livewire 3**
    *   Membuat tampilan menjadi interaktif (seperti React/Vue) tapi tetap menggunakan PHP.
    *   Mengurus fitur "Update Status Real-time" tanpa reload halaman.
*   **Tailwind CSS**
    *   Framework CSS untuk mendesain tampilan agar modern dan responsif (bagus di HP).
*   **Alpine.js**
    *   Javascript ringan untuk interaksi kecil (buka tutup menu, modal popup).

## Database
*   **MySQL / MariaDB**
    *   Tempat menyimpan semua data.

## Server & Tools
*   **Laragon / XAMPP**: Untuk menjalankan server lokal di Windows.
*   **Composer**: Untuk install library PHP.
*   **NPM**: Untuk install library Javascript/CSS.
*   **Git**: Untuk menyimpan riwayat kode.

## Layanan Pihak Ketiga (External)
*   **Midtrans**: Payment Gateway untuk memproses pembayaran QRIS.
*   **Resend / Mailtrap**: Layanan untuk mengirim email notifikasi.
