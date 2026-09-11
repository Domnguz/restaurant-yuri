<nav
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="bg-white border-b border-gray-200"
>

    {{-- Barra superior --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between h-16">

            {{-- Logo / nombre --}}
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3"
            >

                <div class="w-10 h-10 rounded-xl bg-gray-900 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.7">
                        <path d="M5 3v7a3 3 0 003 3v8M5 3v6M8 3v6M11 3v6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 3c-1.5 0-2.5 2-2.5 5s1 5 2.5 5v8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div class="hidden sm:block">

                    <p class="text-sm font-extrabold text-gray-950 leading-none">
                        Restaurant Yuri
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Administración
                    </p>

                </div>

            </a>


            {{-- Navegación escritorio --}}
            <div class="hidden sm:flex items-center gap-2">

                {{-- Inicio --}}
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2 rounded-xl text-sm font-bold transition
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-gray-900 text-white'
                        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-950' }}"
                >
                    Inicio
                </a>


                {{-- Productos --}}
                <a
                    href="{{ route('admin.productos.index') }}"
                    class="px-4 py-2 rounded-xl text-sm font-bold transition
                    {{ request()->routeIs('admin.productos.*')
                        ? 'bg-gray-900 text-white'
                        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-950' }}"
                >
                    Productos
                </a>


                {{-- Categorías --}}
                <a
                    href="{{ route('admin.categorias.index') }}"
                    class="px-4 py-2 rounded-xl text-sm font-bold transition
                    {{ request()->routeIs('admin.categorias.*')
                        ? 'bg-gray-900 text-white'
                        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-950' }}"
                >
                    Categorías
                </a>

            </div>


            {{-- Usuario --}}
            <div class="hidden sm:flex items-center gap-3">

                <a
                    href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-gray-100"
                >

                    <div class="text-right">

                        <p class="text-sm font-bold text-gray-950">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Administrador
                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">

                        <span class="text-sm font-extrabold text-gray-700">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>

                    </div>

                </a>

            </div>


            {{-- Botón móvil --}}
            <button
                @click="open = !open"
                type="button"
                class="sm:hidden w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center text-gray-800 active:scale-95 transition"
            >

                {{-- Menú --}}
                <svg
                    x-show="!open"
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>


                {{-- Cerrar --}}
                <svg
                    x-show="open"
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>

            </button>

        </div>

    </div>


    {{-- Menú móvil --}}
    <div
        x-show="open"
        x-transition
        x-cloak
        class="sm:hidden border-t border-gray-100 bg-white"
    >

        <div class="px-4 py-4 space-y-2">

            {{-- Inicio --}}
            <a
                href="{{ route('admin.dashboard') }}"
                @click="open = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition
                {{ request()->routeIs('admin.dashboard')
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 bg-gray-50' }}"
            >
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 11l8-7 8 7M6 10v9a1 1 0 001 1h3v-6h4v6h3a1 1 0 001-1v-9" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                Inicio
            </a>


            {{-- Productos --}}
            <a
                href="{{ route('admin.productos.index') }}"
                @click="open = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition
                {{ request()->routeIs('admin.productos.*')
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 bg-gray-50' }}"
            >
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M5 3v7a3 3 0 003 3v8M5 3v6M8 3v6M11 3v6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 3c-1.5 0-2.5 2-2.5 5s1 5 2.5 5v8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                Productos
            </a>


            {{-- Categorías --}}
            <a
                href="{{ route('admin.categorias.index') }}"
                @click="open = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition
                {{ request()->routeIs('admin.categorias.*')
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 bg-gray-50' }}"
            >
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3.5" y="3.5" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="13" y="3.5" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="3.5" y="13" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="13" y="13" width="7.5" height="7.5" rx="1.5"/>
                </svg>

                Categorías
            </a>


            {{-- Perfil --}}
            <a
                href="{{ route('profile.edit') }}"
                @click="open = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-700 bg-gray-50"
            >
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="8" r="3.2"/>
                    <path d="M5.5 20a6.5 6.5 0 0113 0" stroke-linecap="round"/>
                </svg>

                Mi perfil
            </a>


            {{-- Cerrar sesión --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
                @submit="open = false"
            >

                @csrf

                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-red-600 bg-red-50"
                >
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 17l5-5-5-5M21 12H9" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    Cerrar sesión
                </button>

            </form>

        </div>

    </div>


    {{-- Navegación inferior para celular --}}
    <div class="sm:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-[0_-4px_15px_rgba(0,0,0,0.06)]">

        <div class="grid grid-cols-4 h-20">

            {{-- Inicio --}}
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex flex-col items-center justify-center gap-1
                {{ request()->routeIs('admin.dashboard')
                    ? 'text-gray-950'
                    : 'text-gray-400' }}"
            >

                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('admin.dashboard') ? '2.1' : '1.8' }}">
                    <path d="M4 11l8-7 8 7M6 10v9a1 1 0 001 1h3v-6h4v6h3a1 1 0 001-1v-9" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <span class="text-[11px] font-bold">
                    Inicio
                </span>

            </a>


            {{-- Productos --}}
            <a
                href="{{ route('admin.productos.index') }}"
                class="flex flex-col items-center justify-center gap-1
                {{ request()->routeIs('admin.productos.*')
                    ? 'text-gray-950'
                    : 'text-gray-400' }}"
            >

                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('admin.productos.*') ? '2.1' : '1.8' }}">
                    <path d="M5 3v7a3 3 0 003 3v8M5 3v6M8 3v6M11 3v6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 3c-1.5 0-2.5 2-2.5 5s1 5 2.5 5v8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <span class="text-[11px] font-bold">
                    Productos
                </span>

            </a>


            {{-- Categorías --}}
            <a
                href="{{ route('admin.categorias.index') }}"
                class="flex flex-col items-center justify-center gap-1
                {{ request()->routeIs('admin.categorias.*')
                    ? 'text-gray-950'
                    : 'text-gray-400' }}"
            >

                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('admin.categorias.*') ? '2.1' : '1.8' }}">
                    <rect x="3.5" y="3.5" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="13" y="3.5" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="3.5" y="13" width="7.5" height="7.5" rx="1.5"/>
                    <rect x="13" y="13" width="7.5" height="7.5" rx="1.5"/>
                </svg>

                <span class="text-[11px] font-bold">
                    Categorías
                </span>

            </a>


            {{-- Perfil --}}
            <a
                href="{{ route('profile.edit') }}"
                class="flex flex-col items-center justify-center gap-1
                {{ request()->routeIs('profile.*')
                    ? 'text-gray-950'
                    : 'text-gray-400' }}"
            >

                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('profile.*') ? '2.1' : '1.8' }}">
                    <circle cx="12" cy="8" r="3.2"/>
                    <path d="M5.5 20a6.5 6.5 0 0113 0" stroke-linecap="round"/>
                </svg>

                <span class="text-[11px] font-bold">
                    Perfil
                </span>

            </a>

        </div>

    </div>

</nav>