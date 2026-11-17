<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#28251f">
    <meta name="description" content="Mr. Yuri, restaurante y tradición. Un lugar para compartir y disfrutar.">
    <meta name="robots" content="index, follow">
    <title>Mr. Yuri | Restaurante y tradición</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="public-home">
    <header class="public-header">
        <a class="public-brand" href="{{ url('/') }}" aria-label="Mr. Yuri, inicio">
            <img src="{{ asset('favicon.svg') }}" alt="" width="42" height="42">
            <span><strong>Mr. Yuri</strong><small>Restaurante &amp; tradición</small></span>
        </a>
        <a class="public-login" href="{{ route('login') }}">Acceso del personal</a>
    </header>

    <main>
        <section class="public-hero" aria-labelledby="hero-title">
            <div class="public-hero-copy">
                <p class="public-eyebrow">Restaurante &amp; tradición</p>
                <h1 id="hero-title">Un lugar para compartir.</h1>
                <p>Bienvenido a Mr. Yuri. Nos alegra recibirte; aquí encontrarás un espacio pensado para disfrutar y compartir.</p>
                <a class="public-cta" href="{{ route('login') }}">Acceso para el equipo <span aria-hidden="true">→</span></a>
            </div>
            <div class="public-hero-image" role="img" aria-label="Ambiente cálido de restaurante"></div>
        </section>

        <section class="public-welcome" aria-labelledby="welcome-title">
            <p class="public-eyebrow">Mr. Yuri</p>
            <h2 id="welcome-title">Restaurante y tradición</h2>
            <p>Te damos la bienvenida a nuestro restaurante, un lugar para compartir y disfrutar.</p>
        </section>
    </main>

    <footer class="public-footer"><span>© {{ date('Y') }} Mr. Yuri</span><span>Restaurante &amp; tradición</span></footer>
</body>
</html>
