<nav
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="bg-white border-b border-gray-200"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            {{-- Logo --}}
            <div class="flex items-center">

                <a
                    href="{{ route('admin.mesero.pedidos.index') }}"
                    class="flex items-center gap-3"
                >

                    <div class="w-10 h-10 rounded-xl bg-gray-900 text-white flex items-center justify-center font-extrabold">
                        Y
                    </div>

                    <div>
                        <p class="font-extrabold text-gray-900">
                            Mr. Yuri
                        </p>

                        <p class="text-xs text-gray-500">
                            Panel del Mesero
                        </p>
                    </div>

                </a>

            </div>


            {{-- Navegación escritorio --}}
            <div class="hidden sm:flex items-center gap-2">

                <a
                    href="{{ route('admin.mesero.pedidos.index') }}"
                    class="px-4 py-2 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-100 transition"
                >
                    🪑 Mesas
                </a>

                <a
                    href="{{ route('profile.edit') }}"
                    class="px-4 py-2 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-100 transition"
                >
                    👤 Mi perfil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-xl text-sm font-bold text-red-600 hover:bg-red-50 transition"
                    >
                        Cerrar sesión
                    </button>
                </form>

            </div>


            {{-- Botón móvil --}}
            <div class="flex items-center sm:hidden">

                <button
                    type="button"
                    @click="open = !open"
                    class="p-2 rounded-xl text-gray-600 hover:bg-gray-100"
                >

                    {{-- Menú --}}
                    <svg
                        x-show="!open"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
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
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>

        </div>

    </div>


    {{-- Menú móvil --}}
    <div
        x-show="open"
        x-transition
        x-cloak
        class="sm:hidden border-t border-gray-100 bg-white"
    >

        <div class="px-4 py-3 space-y-1">

            <a
                href="{{ route('admin.mesero.pedidos.index') }}"
                @click="open = false"
                class="block px-4 py-3 rounded-xl text-gray-700 font-semibold hover:bg-gray-100"
            >
                🪑 Mesas
            </a>

            <a
                href="{{ route('profile.edit') }}"
                @click="open = false"
                class="block px-4 py-3 rounded-xl text-gray-700 font-semibold hover:bg-gray-100"
            >
                👤 Mi perfil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full text-left px-4 py-3 rounded-xl text-red-600 font-semibold hover:bg-red-50"
                >
                    🚪 Cerrar sesión
                </button>

            </form>

        </div>

    </div>

</nav>