@extends('layouts.site', ['title' => 'Kontak Omah Susi Laundry'])

@section('content')
    <section class="mx-auto mt-16 w-full max-w-5xl px-6">
        <div class="text-center">
            <p class="text-sm font-semibold text-primary">Hubungi Kami</p>
            <h1 class="text-4xl font-bold text-slate-900">Bantuan 7 hari seminggu</h1>
            <p class="mt-3 text-slate-600">Pilih cara tercepat untuk terhubung langsung dengan admin.</p>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            <a href="https://wa.me/6285645520620" target="_blank" rel="noopener"
                class="rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-soft hover:border-primary">
                <p class="text-sm font-semibold text-primary">Chat WhatsApp</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">+62 856-455-206-20</p>
                <p class="mt-2 text-sm text-slate-600">Respons maksimal 5 menit.</p>
            </a>
            <a href="tel:085645520620"
                class="rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-soft hover:border-primary">
                <p class="text-sm font-semibold text-primary">Telepon</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">0856-4552-0620</p>
                <p class="mt-2 text-sm text-slate-600">Jam kerja 08.00 - 21.00.</p>
            </a>
            <div class="rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-soft">
                <p class="text-sm font-semibold text-primary">Email</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">halo@susilaundry.paulfjr.my.id</p>
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
                <iframe title="Lokasi Laundry"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.221081119635!2d112.3340806!3d-8.078921000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e789116daa292df%3A0xb3625e005d3365c!2sOMAH%20LAUNDRY%20SUSI!5e0!3m2!1sid!2sid!4v1765056839948!5m2!1sid!2sid"
                    class="w-full" style="border:0; aspect-ratio: 4/3; min-height: 240px;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('order.create') }}"
                class="rounded-2xl border border-primary bg-primary px-5 py-3 text-center text-sm font-semibold text-white">Buat
                Pesanan</a>
            <a href="{{ route('landing') }}"
                class="rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Kembali</a>
        </div>
    </section>
@endsection