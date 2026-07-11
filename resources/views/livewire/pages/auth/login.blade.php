<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        $redirectRoute = $user->isAdmin() ? 'admin.dashboard' : 'karyawan.dashboard';

        $this->redirectIntended(default: route($redirectRoute, absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div class="relative">
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-ganjs-ink" />
            <div class="relative mt-1.5">
                <input 
                    wire:model="form.email" 
                    id="email" 
                    class="input-interactive w-full pl-11" 
                    type="email" 
                    name="email" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="Masukkan email Anda"
                />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-xs" />
        </div>

        <!-- Password -->
        <div x-data="{ showPassword: false }" class="relative">
            <x-input-label for="password" :value="__('Password')" class="font-semibold text-ganjs-ink" />
            <div class="relative mt-1.5">
                <input 
                    wire:model="form.password" 
                    id="password" 
                    :type="showPassword ? 'text' : 'password'" 
                    class="input-interactive w-full pl-11 pr-11" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="Masukkan kata sandi"
                />
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-ganjs-ink-muted/60 hover:text-ganjs-primary transition-colors focus:outline-none"
                    aria-label="Tampilkan kata sandi"
                >
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" style="display:none;" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-xs" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center cursor-pointer select-none">
                <input wire:model="form.remember" id="remember" type="checkbox" class="w-4 h-4 rounded border-ganjs-border text-ganjs-primary focus:ring-ganjs-primary/30 transition duration-150" name="remember">
                <span class="ms-2 text-xs font-semibold text-ganjs-ink-muted hover:text-ganjs-ink transition-colors">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-semibold text-ganjs-primary hover:text-ganjs-primary-dark transition-colors focus:outline-none focus:underline" href="{{ route('password.request') }}" wire:navigate>
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full btn-primary bg-gradient-to-r from-ganjs-primary to-ganjs-primary-dark hover:from-ganjs-primary-dark hover:to-[#9E3109] shadow-btn py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 group transition-all duration-300 active:scale-95"
            >
                <span>Masuk Sekarang</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</div>

