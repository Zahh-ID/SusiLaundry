<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" @keydown.window.escape="sidebarOpen = false">

    {{-- Logo Area --}}
    <div class="flex h-16 items-center justify-between border-b border-slate-800 bg-slate-950 px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-white" wire:navigate>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white">O</div>
            <span>Omah Susi</span>
        </a>

        {{-- Close Button (Mobile) --}}
        <button type="button" class="-m-2.5 p-2.5 text-slate-400 hover:text-white lg:hidden"
            @click="sidebarOpen = false">
            <span class="sr-only">Close sidebar</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 space-y-1 px-3 py-4">
        <a href="{{ route('dashboard') }}" wire:navigate
            class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.orders.index') }}" wire:navigate
            class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            Pesanan
        </a>

        <a href="{{ route('admin.packages.index') }}" wire:navigate
            class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.packages.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.packages.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
            </svg>
            Paket & Layanan
        </a>

        <a href="{{ route('admin.customers.index') }}" wire:navigate
            class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.customers.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.customers.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            Pelanggan
        </a>

        <a href="{{ route('admin.reports.index') }}" wire:navigate
            class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            Laporan
        </a>
    </nav>

    {{-- User Profile & Logout --}}
    <div class="border-t border-slate-800 p-4">
        <div class="flex items-center gap-3">
            <div
                class="h-9 w-9 rounded-full bg-slate-700 flex items-center justify-center text-sm font-bold text-white">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
            </div>
            <button wire:click="logout" class="text-slate-400 hover:text-white" title="Logout">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
            </button>
        </div>
    </div>
</aside>