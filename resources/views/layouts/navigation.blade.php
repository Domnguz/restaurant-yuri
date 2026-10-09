@php
    $isWaiter = auth()->user()->role === 'mesero';
    $links = $isWaiter ? [
        ['route' => 'admin.mesero.pedidos.index', 'match' => 'admin.mesero.*', 'label' => 'Tomar pedidos', 'icon' => 'table'],
    ] : [
        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Resumen', 'icon' => 'home'],
        ['route' => 'admin.pedidos.index', 'match' => 'admin.pedidos.*', 'label' => 'Pedidos y mesas', 'icon' => 'orders'],
        ['route' => 'admin.productos.index', 'match' => 'admin.productos.*', 'label' => 'Carta de productos', 'icon' => 'plate'],
        ['route' => 'admin.categorias.index', 'match' => 'admin.categorias.*', 'label' => 'Categorías', 'icon' => 'categories'],
    ];
@endphp
<div x-show="menuOpen" x-cloak x-transition.opacity @click="menuOpen = false" class="fixed inset-0 z-50 bg-black/30 md:hidden"></div>
<aside id="app-navigation" class="app-sidebar" :class="{ 'is-open': menuOpen }" @click.outside="menuOpen = false">
    <a href="{{ route($links[0]['route']) }}" class="px-3"><x-brand /></a>
    <p class="nav-label">{{ $isWaiter ? 'ATENCIÓN AL CLIENTE' : 'TU RESTAURANTE' }}</p>
    <nav aria-label="Navegación principal">
        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}" class="nav-item {{ request()->routeIs($link['match']) ? 'active' : '' }}" @if(request()->routeIs($link['match'])) aria-current="page" @endif><x-icon :name="$link['icon']" />{{ $link['label'] }}</a>
        @endforeach
    </nav>
    <div class="sidebar-bottom">
        <div class="sidebar-note"><strong>Una buena atención empieza aquí.</strong>Pedidos claros. Mesas organizadas. Clientes felices.</div>
        <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"><x-icon name="user" /> Mi perfil</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-item w-full" type="submit"><x-icon name="logout" />Cerrar sesión</button></form>
        <p class="text-[10px] text-gray-400 px-4 mt-4">MR. YURI · HECHO PARA ATENDER MEJOR</p>
    </div>
</aside>
<nav class="bottom-nav" aria-label="Accesos en celular">
    @foreach ($links as $link)
        <a href="{{ route($link['route']) }}" class="{{ request()->routeIs($link['match']) ? 'active' : '' }}"><x-icon :name="$link['icon']" /><span>{{ $link['icon'] === 'orders' ? 'Pedidos' : ($link['icon'] === 'plate' ? 'Productos' : $link['label']) }}</span></a>
    @endforeach
    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><x-icon name="user" /><span>Perfil</span></a>
</nav>
