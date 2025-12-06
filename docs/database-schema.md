# Skema Database (Struktur Data)

Dokumen ini menjelaskan tabel-tabel yang ada di database. Bayangkan ini sebagai daftar "Buku Catatan" yang dimiliki laundry.

## Diagram Hubungan Antar Tabel (ERD)
Gambar di bawah menjelaskan bagaimana satu data berhubungan dengan data lain.

```mermaid
erDiagram
    USERS ||--o{ ORDERS : "mengelola"
    CUSTOMERS ||--o{ ORDERS : "memesan"
    PACKAGES ||--o{ ORDERS : "berisi"
    ORDERS ||--o{ PAYMENTS : "memiliki"
    USERS ||--o{ ACTIVITY_LOGS : "mencatat"

    USERS {
        bigint id PK
        string name "Nama Admin"
        string email "Login Admin"
        string password
    }

    CUSTOMERS {
        bigint id PK "ID Pelanggan"
        string name "Nama"
        string phone "No HP"
        string email
        text address "Alamat"
    }

    PACKAGES {
        bigint id PK
        string package_name "Nama Paket (Cuci Komplit)"
        decimal price_per_kg "Harga/kg"
        int turnaround_hours "Estimasi Jam Selesai"
        string billing_type "Hitungan (kg/satuan)"
    }

    ORDERS {
        bigint id PK
        string order_code "Kode Unik 10-huruf"
        foreignId customer_id FK "Milik Siapa"
        foreignId package_id FK "Paket Apa"
        foreignId admin_id FK "Siapa yang input"
        string status "Proses sekarang"
        string payment_status "Lunas/Belum"
        string payment_method "Tunai/QRIS"
        decimal total_price "Total Rupiah"
        decimal actual_weight "Berat Asli"
    }

    PAYMENTS {
        bigint id PK
        foreignId order_id FK "Bayar untuk order mana"
        string method "Metode"
        string status "Sukses/Gagal/Pending"
        decimal amount "Jumlah Bayar"
        string qris_image_url "Link Gambar QR"
        timestamp expiry_time "Kapan QR kadaluarsa"
    }
```

---

## Penjelasan Tabel

### 1. Users (Admin)
Daftar orang yang bisa login ke halaman admin.
*   `email`: Digunakan untuk login.
*   `password`: Kata sandi (rahasia).

### 2. Packages (Daftar Menu)
Daftar layanan yang dijual. Contoh: "Cuci Kering", "Setrika Saja".
*   `price_per_kg`: Harga dasar.
*   `turnaround_hours`: Berapa lama cucian biasanya selesai (contoh: 48 jam).

### 3. Customers (Pelanggan)
Buku telepon pelanggan. Ini tersimpan otomatis saat membuat pesanan baru.

### 4. Orders (Pesanan)
Tabel paling penting. Mencatat setiap transaksi laundry.
*   `order_code`: Kode acak (misal: `TRX998877`) yang dikasih ke pelanggan untuk cek resi.
*   `status`: Posisi cucian sekarang (Pending -> Proses -> Selesai).
*   `payment_status`: Status uang (Paid = Lunas, Unpaid = Belum).

### 5. Payments (Riwayat Pembayaran)
Mencatat setiap kali ada percobaan pembayaran.
*   Kenapa dipisah dari Order? Karena satu order bisa punya banyak percobaan bayar (misal: kemarin QRIS gagal, hari ini coba lagi QRIS baru).
*   Menyimpan link gambar QRIS dan waktu kadaluarsanya.
