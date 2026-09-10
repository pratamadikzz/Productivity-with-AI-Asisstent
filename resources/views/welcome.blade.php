<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Productivity OS membantu Anda mengelola tugas, proyek, kebiasaan, dan tujuan dalam satu ruang kerja yang tenang.">
    <title>Productivity OS | Work with intention</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="landing-page">
    <div class="landing-shell">
        <nav class="landing-nav" aria-label="Navigasi utama">
            <a href="{{ url('/') }}" class="brand-mark" aria-label="Productivity OS home"><span
                    class="brand-icon"><span></span><span></span><span></span></span><span>productivity<span
                        class="brand-dot">.</span>os</span></a>
            <div class="nav-links"><a href="#features">Fitur</a><a href="#method">Cara kerja</a><a
                    href="#focus">Fokus</a></div>
            <div class="nav-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-login">Buka workspace</a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="nav-login">Masuk</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="button button-small">Mulai gratis <span>↗</span></a>
                    @endif
                @endauth
            </div>
        </nav>

        <main>
            <section class="hero-section">
                <div class="hero-copy reveal">
                    <div class="eyebrow"><span class="status-dot"></span> Ruang kerja yang dibuat untuk maju</div>
                    <h1>Make space for<br><em>meaningful work.</em></h1>
                    <p class="hero-description">Satu ruang tenang untuk mengubah niat menjadi progres. Kelola tugas,
                        proyek, kebiasaan, dan tujuan tanpa kehilangan fokus pada hal yang penting.</p>
                    <div class="hero-actions">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="button">Masuk ke dashboard <span>↗</span></a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="button">Mulai workspace Anda <span>↗</span></a>
                            @endif
                        @endauth
                        <a href="#features" class="text-link">Lihat yang bisa dilakukan <span>↓</span></a>
                    </div>
                    <div class="hero-note"><span class="avatars"><i>R</i><i>A</i><i>M</i></span><span>Dipakai oleh
                            orang-orang yang ingin bekerja dengan lebih sadar.</span></div>
                </div>

                <div class="workspace-preview reveal delay-one" aria-label="Preview dashboard Productivity OS">
                    <div class="preview-glow"></div>
                    <div class="preview-window">
                        <div class="preview-sidebar">
                            <div class="mini-brand"><span
                                    class="brand-icon"><span></span><span></span><span></span></span></div>
                            <div class="mini-nav-label">WORKSPACE</div>
                            <div class="mini-nav active"><b>⌂</b> Overview</div>
                            <div class="mini-nav"><b>□</b> Tasks <small>12</small></div>
                            <div class="mini-nav"><b>◫</b> Projects</div>
                            <div class="mini-nav"><b>◷</b> Calendar</div>
                            <div class="mini-nav-label mini-space">PERSONAL</div>
                            <div class="mini-nav"><b>◌</b> Goals</div>
                            <div class="mini-nav"><b>◇</b> Habits</div>
                            <div class="mini-nav"><b>▤</b> Notes</div>
                            <div class="mini-profile"><span>DS</span>
                                <div>Dina S.<small>Personal plan</small></div><b>•••</b>
                            </div>
                        </div>
                        <div class="preview-main">
                            <div class="preview-top"><span>Monday, 08 September 2026</span><span
                                    class="preview-bell">◉</span></div>
                            <div class="preview-heading">
                                <div>
                                    <h2>Good morning, Dina <span>✦</span></h2>
                                    <p>Let's make today count.</p>
                                </div><button>+ New task</button>
                            </div>
                            <div class="preview-stats">
                                <div><span>Tasks completed</span><strong>08 <small>/ 12</small></strong><i
                                        class="stat-line green"></i><em>+12% this week</em></div>
                                <div><span>Focus time</span><strong>04<span class="stat-unit">h</span> 20<span
                                            class="stat-unit">m</span></strong><i class="stat-line coral"></i><em>On
                                        your best streak</em></div>
                                <div><span>Current streak</span><strong>06 <span
                                            class="stat-unit">days</span></strong><i
                                        class="stat-line yellow"></i><em>Keep it going</em></div>
                            </div>
                            <div class="preview-lower">
                                <div class="task-panel">
                                    <div class="panel-title"><strong>Today's focus</strong><span>View all →</span></div>
                                    <div class="task-item done"><b>✓</b><span>Review Q3 product
                                            roadmap</span><small>09:00</small></div>
                                    <div class="task-item"><b></b><span>Prepare client
                                            presentation</span><small>11:30</small></div>
                                    <div class="task-item"><b></b><span>30 min deep work
                                            session</span><small>14:00</small></div>
                                    <div class="task-item"><b></b><span>Write weekly
                                            reflection</span><small>16:30</small></div>
                                </div>
                                <div class="focus-panel">
                                    <div class="panel-title"><strong>Weekly focus</strong><span>···</span></div>
                                    <div class="chart"><i style="height: 35%"></i><i style="height: 55%"></i><i
                                            style="height: 45%"></i><i style="height: 72%"></i><i class="today"
                                            style="height: 88%"></i><i style="height: 64%"></i><i
                                            style="height: 42%"></i></div>
                                    <div class="chart-days">
                                        <span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span><span>S</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="trust-strip" id="features"><span>ONE SPACE FOR
                    YOUR</span><strong>Tasks</strong><i></i><strong>Projects</strong><i></i><strong>Goals</strong><i></i><strong>Better
                    habits</strong></section>
            <section class="feature-section" id="method">
                <div class="section-intro">
                    <div class="eyebrow">WHY PRODUCTIVITY OS</div>
                    <h2>Less managing.<br><em>More doing.</em></h2>
                </div>
                <div class="feature-grid">
                    <article><span class="feature-number">01</span>
                        <div class="feature-icon coral-icon">↗</div>
                        <h3>See the whole picture</h3>
                        <p>Semua pekerjaan Anda terhubung dalam satu pandangan yang jernih, sehingga Anda tahu apa yang
                            harus dikerjakan berikutnya.</p><a href="#focus">Explore workspace <span>↗</span></a>
                    </article>
                    <article><span class="feature-number">02</span>
                        <div class="feature-icon green-icon">◒</div>
                        <h3>Build momentum</h3>
                        <p>Ubah langkah kecil menjadi ritme. Lacak kebiasaan dan rayakan progres yang membuat Anda terus
                            bergerak.</p><a href="#focus">Find your rhythm <span>↗</span></a>
                    </article>
                    <article><span class="feature-number">03</span>
                        <div class="feature-icon yellow-icon">✦</div>
                        <h3>Focus on what matters</h3>
                        <p>Prioritaskan hal yang benar-benar berdampak, lalu sisakan ruang untuk berpikir dan bekerja
                            dengan dalam.</p><a href="#focus">Make it yours <span>↗</span></a>
                    </article>
                </div>
            </section>
            <section class="closing-section" id="focus">
                <div>
                    <div class="eyebrow">YOUR NEXT CHAPTER</div>
                    <h2>Progress feels<br><em>better together.</em></h2>
                </div>
                <div>
                    <p>Mulai dari satu hal kecil hari ini. Workspace Anda siap membantu menjadikannya sesuatu yang
                        berarti.</p>@guest @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="button">Buat akun gratis <span>↗</span></a>
                @endif @else<a href="{{ url('/dashboard') }}" class="button">Lanjutkan
                    progres <span>↗</span></a>@endguest
            </div>
        </section>
        +
    </main>
    <footer class="landing-footer"><span>© {{ date('Y') }} Productivity OS</span><span>Built for intentional
            progress <b>✦</b></span></footer>
</div>
</body>

</html>
