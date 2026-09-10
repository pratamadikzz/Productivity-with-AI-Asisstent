<x-guest-layout>
    <x-slot:title>Daftar</x-slot:title>
    <div class="auth-heading">
        <span class="auth-kicker">Start with clarity</span>
        <h2>Bangun ritme<br><em>terbaikmu.</em></h2>
        <p>Satu workspace untuk pekerjaan yang ingin Anda selesaikan.</p>
    </div>

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input id="name" class="auth-input" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" placeholder="Nama Anda" />
            <x-input-error :messages="$errors->get('name')" class="auth-error" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required
                autocomplete="username" placeholder="nama@contoh.com" />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />

            <x-text-input id="password" class="auth-input" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi password" />

            <x-text-input id="password_confirmation" class="auth-input" type="password" name="password_confirmation"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
        </div>

        <div class="auth-submit-row auth-register-submit">
            <a class="auth-link" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="auth-button">
                Daftar <span>→</span>
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
