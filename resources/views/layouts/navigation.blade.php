<nav
    x-data="{ open: false }"
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
                    <span class="text-lg">
                        🍽️
                    </span>
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
        class="sm:hidden border-t border-gray-100 bg-white"
    >

        <div class="px-4 py-4 space-y-2">

            {{-- Inicio --}}
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition
                {{ request()->routeIs('admin.dashboard')
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 bg-gray-50' }}"
            >
                <span class="text-lg">
                    🏠
                </span>

                Inicio
            </a>


            {{-- Productos --}}
            <a
                href="{{ route('admin.productos.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition
                {{ request()->routeIs('admin.productos.*')
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 bg-gray-50' }}"
            >
                <span class="text-lg">
                    🍽️
                </span>

                Productos
            </a>


            {{-- Categorías --}}
            <a
                href="{{ route('admin.categorias.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition
                {{ request()->routeIs('admin.categorias.*')
                    ? 'bg-gray-900 text-white'
                    : 'text-gray-700 bg-gray-50' }}"
            >
                <span class="text-lg">
                    🗂️
                </span>

                Categorías
            </a>


            {{-- Perfil --}}
            <a
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-gray-700 bg-gray-50"
            >
                <span class="text-lg">
                    👤
                </span>

                Mi perfil
            </a>


            {{-- Cerrar sesión --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-red-600 bg-red-50"
                >
                    <span class="text-lg">
                        ↪
                    </span>

                    Cerrar sesión
                </button>

            </form>

        </div>

    </div>


    {{-- Navegación inferior para celular --}}
    <div class="sm:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-[0_-4px_15px_rgba(0,0,0,0.06)]">

        <div class="grid grid-cols-3 h-20">

            {{-- Inicio --}}
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex flex-col items-center justify-center gap-1
                {{ request()->routeIs('admin.dashboard')
                    ? 'text-gray-950'
                    : 'text-gray-400' }}"
            >

                <span class="text-xl">
                    🏠
                </span>

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

                <span class="text-xl">
                    🍽️
                </span>

                <span class="text-[11px] font-bold">
                    Productos
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

                <span class="text-xl">
                    👤
                </span>

                <span class="text-[11px] font-bold">
                    Perfil
                </span>

            </a>

        </div>

    </div>

</nav>