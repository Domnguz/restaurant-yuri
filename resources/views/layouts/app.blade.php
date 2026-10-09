<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#c85120">
    <title>Mr. Yuri · {{ auth()->user()->role === 'mesero' ? 'Atención al cliente' : 'Administración' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased" data-realtime="true" data-realtime-port="{{ config('services.realtime.port') }}" data-realtime-url="{{ config('services.realtime.public_url') }}" data-snapshot-url="{{ route('realtime.snapshot') }}" data-token-url="{{ route('realtime.connection') }}" x-data="{ menuOpen: false }" @keydown.escape.window="menuOpen = false">
    <a href="#contenido" class="skip-link">Ir al contenido</a>
    @include('layouts.navigation')
    <div class="app-workspace">
        <header class="app-topbar">
            <div class="flex items-center gap-3">
                <button class="mobile-menu-button" @click.stop="menuOpen = !menuOpen" :aria-expanded="menuOpen" aria-label="Abrir navegación" aria-controls="app-navigation"><x-icon name="menu" /></button>
                <div><span class="text-sm font-semibold">{{ auth()->user()->role === 'mesero' ? 'Atención en salón' : 'Panel de administración' }}</span><p class="text-xs text-gray-500 flex items-center gap-2 mt-1" role="status"><span class="w-2 h-2 rounded-full shrink-0" :class="$store.restaurant.connected && !$store.restaurant.failed ? 'bg-green-600' : 'bg-amber-600'"></span><span x-text="$store.restaurant.label">Conectando…</span></p></div>
            </div>
            <div class="flex items-center gap-5">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3" aria-label="Mi perfil">
                    <span class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <div class="hidden sm:block"><p class="text-sm font-semibold">{{ auth()->user()->name }}</p><p class="text-xs text-gray-400">{{ auth()->user()->role === 'mesero' ? 'Mesero' : 'Administrador' }}</p></div>
                </a>
            </div>
        </header>
        <main id="contenido" class="app-main">
            @isset($header)<div class="page-header">{{ $header }}</div>@endisset
            {{ $slot }}
        </main>
    </div>
    <div x-show="$store.restaurant.notice" x-cloak class="fixed bottom-24 md:bottom-6 right-4 left-4 sm:left-auto z-40 rounded-xl bg-gray-900 text-white p-4 shadow-lg sm:max-w-sm text-sm" role="status" aria-live="polite" x-text="$store.restaurant.notice"></div>
    <div x-show="$store.restaurant.expired" x-cloak class="fixed bottom-24 md:bottom-6 left-4 right-4 z-50 rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-900" role="alert">Tu sesión venció. <a href="{{ route('login') }}" class="font-semibold underline">Vuelve a ingresar para continuar.</a></div>
</body>
</html>
