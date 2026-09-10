<x-guest-layout>
    <x-slot:title>Masuk</x-slot:title>
    <div class="auth-heading">
        <span class="auth-kicker">Welcome back</span>
        <h2>Selamat datang<br>kembali.</h2>
        <p>Masuk untuk melanjutkan workspace Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" placeholder="nama@contoh.com" />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />

            <x-text-input id="password" class="auth-input" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <!-- Remember Me -->
        <div class="auth-options">
            <label for="remember_me" class="auth-check">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="auth-submit-row">
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif

            <x-primary-button class="auth-button">
                Masuk <span>→</span>
            </x-primary-button>
        </div>
    </form>

    <p class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Buat akun gratis <span>↗</span></a></p>
</x-guest-layout>
