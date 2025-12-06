# Diagram Alur Pesanan (Flowchart)

Gambar ini menjelaskan langkah-langkah dari pelanggan datang sampai cucian selesai.

```mermaid
flowchart TD
    Start([Pelanggan Datang]) --> Type{Tipe Order?}
    
    %% Walk-in Flow
    Type -- Walk-in --> AdminInput["Admin Input Pesanan<br>(Formulir Admin)"]
    AdminInput --> PkgSelect[Pilih Paket]
    PkgSelect --> Details[Isi Data Pelanggan]
    Details --> Weight["Isi Berat (Kg)"]
    
    Weight --> PayMethod{Cara Bayar?}
    
    %% Cash Payment
    PayMethod -- Tunai --> CashConfirm{Sudah Terima Uang?}
    CashConfirm -- Ya --> MarkPaid["Status: LUNAS"]
    CashConfirm -- Tidak --> MarkUnpaid["Status: BELUM LUNAS"]
    
    %% QRIS Payment
    PayMethod -- QRIS --> GenQR[Sistem Buat QRIS]
    GenQR --> ShowQR[Tampil QR di Web Tracking]
    
    MarkPaid --> SaveOrder[Simpan Order]
    MarkUnpaid --> SaveOrder
    ShowQR --> SaveOrder
    
    SaveOrder --> Process["Status: Diproses"]
    
    %% Processing Loop & Payment Check
    Process --> WashDry[Cuci & Setrika...]
    WashDry --> Ready["Status: Siap Diambil"]
    
    Ready --> Pickup{Pelanggan Ambil}
    
    Pickup --> CheckPay{Sudah Lunas?}
    CheckPay -- Belum --> PaymentAlert[Muncul Peringatan Bayar]
    PaymentAlert --> PayNow[Terima Pembayaran Dulu]
    PayNow --> CheckPay
    
    CheckPay -- Sudah --> Handover[Serahkan Barang]
    Handover --> Taken["Status: Diambil<br>(Selesai)"]
    Taken --> End([Tamat])
```

## Penjelasan Singkat
1.  **Input**: Admin memasukkan data.
2.  **Bayar**:
    *   Kalau **Tunai**, admin konfirmasi langsung.
    *   Kalau **QRIS**, sistem buatkan kodenya, pelanggan scan sendiri nanti.
3.  **Proses**: Baju dicuci.
4.  **Kunci Pengambilan**: Barang **TIDAK BISA** diambil kalau sistem bilang "Belum Lunas". Harus dilunasi dulu baru sistem membolehkan status berubah jadi "Diambil".
