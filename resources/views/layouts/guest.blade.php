<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#c85120">
    <title>Mr. Yuri · Bienvenido</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="auth-layout">
        <section class="auth-story">
            <a href="{{ route('login') }}"><x-brand /></a>
            <div class="relative z-10 my-12">
                <p class="eyebrow !text-orange-300">EL SABOR DE UNA BUENA ATENCIÓN</p>
                <h1>Más tiempo para<br>tus clientes.<br><span class="text-orange-300">Todo bajo control.</span></h1>
                <p class="text-base leading-7">Desde el primer pedido hasta la última mesa. Organiza el día de tu restaurante con la sencillez que tu equipo necesita.</p>
                <div class="flex flex-wrap gap-3 mt-8 text-xs text-gray-300"><span class="border border-white/15 rounded-full px-4 py-2">Pedidos por mesa</span><span class="border border-white/15 rounded-full px-4 py-2">Carta administrable</span><span class="border border-white/15 rounded-full px-4 py-2">Control de cobros</span></div>
            </div>
            <p class="text-xs">Mr. Yuri · Restaurante & tradición</p>
        </section>
        <section class="auth-form-side">
            <div class="auth-card">
                <a href="{{ route('login') }}" class="block"><x-brand /></a>
                {{ $slot }}
                <p class="text-xs text-gray-400 text-center mt-8">Acceso para el equipo del restaurante</p>
            </div>
        </section>
    </div>
</body>
</html>
