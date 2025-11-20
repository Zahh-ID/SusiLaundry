<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pesanan {{ $order->order_code }}</title>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { max-width: 700px; margin: 0 auto; padding: 24px; color: #0f172a; }
        h1 { font-size: 24px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 8px; border: 1px solid #e2e8f0; text-align: left; font-size: 14px; }
        .text-right { text-align: right; }
    </style>
</head>
<body onload="window.print()">
    <h1>Nota Pesanan</h1>
    <p>Kode Pesanan: <strong>{{ $order->order_code }}</strong></p>
    <p>Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>

    <h3>Data Pelanggan</h3>
    <p>Nama: {{ $order->customer?->name }}</p>
    <p>Email: {{ $order->customer?->email ?? $order->customer?->phone ?? '-' }}</p>
    <p>Alamat: {{ $order->customer?->address }}</p>

    <h3>Detail Pesanan</h3>
    <table>
        <tr>
            <th>Paket</th>
            <td>{{ $order->package?->package_name }}</td>
        </tr>
        <tr>
            <th>Layanan</th>
            <td>{{ ucfirst($order->service_type) }}</td>
        </tr>
        <tr>
            <th>Berat Estimasi</th>
            <td>{{ $order->estimated_weight }} kg</td>
        </tr>
        <tr>
            <th>Berat Aktual</th>
            <td>{{ $order->actual_weight ?? '-' }} kg</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $order->status }}</td>
        </tr>
        <tr>
            <th>Catatan</th>
            <td>{{ $order->notes ?? '-' }}</td>
        </tr>
        <tr>
            <th>Total Harga</th>
            <td class="text-right">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p style="margin-top: 24px;">Terima kasih telah menggunakan layanan Susi Laundry.</p>
</body>
</html>
