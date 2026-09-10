<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Masuk' }} | {{ config('app.name', 'Productivity OS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-mono:400|manrope:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-showcase" aria-label="Productivity OS">
            <a class="auth-brand" href="{{ url('/') }}" aria-label="Productivity OS home">
                <span class="auth-brand-icon" aria-hidden="true"><i></i><i></i><i></i></span>
                <span>productivity<span>.</span>os</span>
            </a>

            <div class="auth-showcase-copy">
                <span class="auth-kicker">A calmer way to work</span>
                <h1>Make space for<br><em>meaningful</em> work.</h1>
                <p>Organize your tasks, habits, and ideas in one clear workspace built for momentum.</p>
            </div>

            <div class="auth-quote">
                <span class="auth-quote-mark">“</span>
                <p>Small steps, clearly seen, become remarkable progress.</p>
            </div>
            <span class="auth-orbit auth-orbit-one" aria-hidden="true"></span>
            <span class="auth-orbit auth-orbit-two" aria-hidden="true"></span>
        </section>

        <section class="auth-form-panel">
            <div class="auth-form-wrap">
                {{ $slot }}
            </div>
            <p class="auth-footer">© {{ date('Y') }} Productivity OS <span>Built for better days.</span></p>
        </section>
    </main>
    </div>
</body>

</html>
