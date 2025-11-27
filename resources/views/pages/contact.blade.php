@extends('layouts.site', ['title' => 'Kontak Omah Susi Laundry'])

@section('content')
    <section class="mx-auto mt-16 w-full max-w-5xl px-6">
        <div class="text-center">
            <p class="text-sm font-semibold text-primary">Hubungi Kami</p>
            <h1 class="text-4xl font-bold text-slate-900">Bantuan 7 hari seminggu</h1>
            <p class="mt-3 text-slate-600">Pilih cara tercepat untuk terhubung langsung dengan admin.</p>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            <a href="https://wa.me/6285645520620" target="_blank" rel="noopener" class="rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-soft hover:border-primary">
                <p class="text-sm font-semibold text-primary">Chat WhatsApp</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">+62 856-455-206-20</p>
                <p class="mt-2 text-sm text-slate-600">Respons maksimal 5 menit.</p>
            </a>
            <a href="tel:+62211234567" class="rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-soft hover:border-primary">
                <p class="text-sm font-semibold text-primary">Telepon</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">(021) 123-4567</p>
                <p class="mt-2 text-sm text-slate-600">Jam kerja 08.00 - 21.00.</p>
            </a>
            <div class="rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-soft">
                <p class="text-sm font-semibold text-primary">Email</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">halo@susilaundry.id</p>
                <p class="mt-2 text-sm text-slate-600">Respon maksimal 1x24 jam.</p>
            </div>
        </div>
        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-900">Jam Operasional</h2>
                <ul class="mt-4 space-y-1 text-sm text-slate-600">
                    <li>Senin - Jumat: 08.00 - 21.00</li>
                    <li>Sabtu: 09.00 - 18.00</li>
                    <li>Minggu & Hari Besar: Customer support tetap online.</li>
                </ul>
                <p class="mt-4 text-sm text-slate-500">Antar-jemput dapat dijadwalkan sesuai kebutuhan.</p>
            </div>
            <div class="overflow-hidden rounded-3xl border border-slate-100 shadow-soft">
                <iframe
                    title="Lokasi Laundry"
                    src="https://www.google.com/maps?q=maps.app.goo.gl/nPgsQ1A79ij54K8V6&output=embed"
                    class="w-full"
                    style="border:0; aspect-ratio: 4/3; min-height: 240px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('order.create') }}" class="rounded-2xl border border-primary bg-primary px-5 py-3 text-center text-sm font-semibold text-white">Buat Pesanan</a>
            <a href="{{ route('landing') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Kembali</a>
        </div>
    </section>
@endsection
