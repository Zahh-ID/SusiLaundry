# Dokumentasi API

Susi Laundry menyediakan antarmuka API (Application Programming Interface) untuk integrasi dengan sistem luar atau aplikasi mobile.

## URL Dasar
Semua request ke API harus diawali dengan:
`http://localhost:8000/api`

---

## 1. API Publik (Tanpa Login)
Bisa diakses siapa saja, biasanya untuk aplikasi pelanggan.

### **GET** `/paket`
Mengambil daftar paket laundry yang tersedia.
*   **Response**: JSON daftar paket (Nama, Harga, Durasi).

### **GET** `/tracking/{kode}`
Mengecek status pesanan berdasarkan kode unik.
*   **Parameter**: `kode` (Contoh: `TRX123456`).
*   **Response**: Detail pesanan, status terkini, dan riwayat.

### **POST** `/order/store`
Membuat pesanan baru dari luar (misal dari aplikasi HP).
*   **Body**:
    *   `customer_name`: String
    *   `phone`: String
    *   `package_id`: Integer
    *   `weight`: Decimal
*   **Response**: Data pesanan baru dan kode tracking.

---

## 2. API Webhook (Integrasi Pembayaran)

### **POST** `/webhooks/midtrans`
Endpoint khusus untuk menerima notifikasi dari Midtrans (Payment Gateway).
*   Jangan panggil ini manual. Ini dipanggil otomatis oleh server Midtrans saat ada pembayaran masuk.
*   **Fungsi**: Mengubah status pembayaran di database menjadi 'Paid' otomatis.

---

## 3. API Admin (Butuh Token)
Hanya bisa diakses jika menyertakan Token Autentikasi Admin.
*   **GET** `/admin/orders`: List semua order.
*   **GET** `/admin/packages`: List semua paket.
