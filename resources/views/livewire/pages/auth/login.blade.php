<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Admin Login</h2>
        <p class="text-sm text-slate-600 mt-2">
            Silakan masuk untuk mengelola sistem.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email</label>
            <div class="mt-2">
                <input wire:model="form.email" id="email" name="email" type="email" autocomplete="email" required
                    class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 transition-all"
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium leading-6 text-slate-900">Password</label>
                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="font-medium text-primary hover:text-indigo-500">
                            Lupa password?
                        </a>
                    </div>
                @endif
            </div>
            <div class="mt-2">
                <input wire:model="form.password" id="password" name="password" type="password"
                    autocomplete="current-password" required
                    class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 transition-all">
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="form.remember" id="remember" name="remember" type="checkbox"
                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary">
            <label for="remember" class="ml-3 block text-sm leading-6 text-slate-700">Ingat saya</label>
        </div>

        <div>
            <button type="submit"
                class="flex w-full justify-center rounded-lg bg-primary px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all">
                Masuk
            </button>
        </div>
    </form>


</div>