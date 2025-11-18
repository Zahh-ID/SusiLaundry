@extends('layouts.site', ['title' => 'Kontak Omah Susi Laundry'])

@section('content')
    <section class="mx-auto mt-16 w-full max-w-5xl px-6">
        <div class="text-center">
            <p class="text-sm font-semibold text-primary">Hubungi Kami</p>
            <h1 class="text-4xl font-bold text-slate-900">Bantuan 7 hari seminggu</h1>
            <p class="mt-3 text-slate-600">Pilih cara tercepat untuk terhubung langsung dengan admin.</p>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-soft hover:border-primary">
                <p class="text-sm font-semibold text-primary">Chat WhatsApp</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">+62 812-3456-7890</p>
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
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.8667278256494!2d110.40302727604154!3d-7.482363173622939!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a144115d67fb9%3A0x50d0e53fcd1ebdb0!2sLaundry!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid"
                    width="100%"
                    height="320"
                    style="border:0;"
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
