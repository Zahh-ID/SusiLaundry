<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-primary">Kelola Layanan</p>
            <h1 class="text-3xl font-bold text-slate-900">Paket Laundry</h1>
            <p class="text-sm text-slate-500">Tambah, ubah, atau hapus paket yang tampil di halaman guest.</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="create"
                class="inline-flex items-center justify-center rounded-full border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Tambah Paket
            </button>
        </div>
    </div>
    @if (session()->has('message'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('message') }}
        </div>
    @endif
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Deskripsi</th>
                        <th class="px-4 py-2">Harga</th>
                        <th class="px-4 py-2">Jenis</th>
                        <th class="px-4 py-2">Durasi (jam)</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $package->package_name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $package->description }}</td>
                            <td class="px-4 py-3 font-semibold">Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                {{ ucfirst(str_replace('_', ' ', $package->billing_type)) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $package->turnaround_hours }}</td>
                            <td class="px-4 py-3 text-sm font-semibold">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.packages.edit', $package) }}"
                                       class="inline-flex items-center justify-center rounded-lg border border-amber-200 bg-amber-50 p-2 text-amber-600 shadow-sm hover:border-amber-300 hover:bg-amber-100 hover:text-amber-700 transition-all font-medium"
                                       title="Edit Paket">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="sr-only">Edit</span>
                                    </a>

                                    <button type="button"
                                        onclick="confirm('Hapus paket {{ $package->package_name }}?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $package->id }})"
                                        class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 shadow-sm hover:border-red-300 hover:bg-red-100 hover:text-red-700 transition-all font-medium"
                                        title="Hapus Paket">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="sr-only">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada paket yang
                                terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-3 py-5">
            <div class="relative w-full max-w-2xl transform rounded-2xl sm:rounded-3xl bg-white p-8 shadow-2xl animate-fade-in-up overflow-hidden"
                @click.away="$wire.closeModal()">

                {{-- Close Button --}}
                <button type="button"
                    class="absolute right-6 top-6 z-10 inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow hover:border-primary hover:text-primary"
                    wire:click="closeModal">
                    ✕ <span>Tutup</span>
                </button>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">Buat Paket Baru</h2>
                    <p class="mt-2 text-sm text-slate-500">Isi detail paket laundry baru.</p>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div>
                        <label for="package_name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Paket</label>
                        <input type="text" id="package_name" wire:model="package_name"
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @error('package_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                        <textarea id="description" rows="3" wire:model="description"
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                        @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="price_per_kg" class="block text-sm font-semibold text-slate-700 mb-2">Harga</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                            <input type="number" id="price_per_kg" wire:model="price_per_kg"
                                class="w-full rounded-xl border-slate-200 pl-10 pr-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                        @error('price_per_kg') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="billing_type" class="block text-sm font-semibold text-slate-700 mb-2">Jenis
                                Tagihan</label>
                            <select id="billing_type" wire:model="billing_type"
                                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <option value="per_kg">Per Kg</option>
                                <option value="per_item">Per Item</option>
                                <option value="paket">Paket</option>
                            </select>
                            @error('billing_type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="turnaround_hours" class="block text-sm font-semibold text-slate-700 mb-2">Durasi
                                (jam)</label>
                            <input type="number" id="turnaround_hours" wire:model="turnaround_hours"
                                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            @error('turnaround_hours') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="pt-6 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" wire:click="closeModal"
                            class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/25 hover:bg-indigo-600 transition-all hover:scale-[1.02]">
                            Simpan Paket
                        </button>
                    </div>
                </form>
            </div>
            <style>
                body {
                    overflow: hidden;
                }
            </style>
        </div>
    @endif
</div>