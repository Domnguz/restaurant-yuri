<x-app-layout>

    <x-slot name="header">

        <div>

            <p class="text-sm font-semibold text-gray-600">
                Productos
            </p>

            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                Nuevo producto
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Registra un nuevo producto para Restaurant Yuri.
            </p>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- Errores de validación --}}
            @if ($errors->any())

                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4">

                    <p class="font-bold text-red-800">
                        Revisa los siguientes datos:
                    </p>

                    <ul class="mt-2 space-y-1 text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                • {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Formulario --}}
            <form
                method="POST"
                action="{{ route('admin.productos.store') }}"
                class="space-y-5"
            >

                @csrf


                {{-- Información --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">

                    <div class="mb-5">

                        <h3 class="text-lg font-bold text-gray-950">
                            Información del producto
                        </h3>

                        <p class="mt-1 text-sm text-gray-600">
                            Completa los datos principales.
                        </p>

                    </div>


                    {{-- Nombre --}}
                    <div>

                        <label
                            for="nombre"
                            class="block text-sm font-bold text-gray-800"
                        >
                            Nombre del producto
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old('nombre') }}"
                            placeholder="Ej. Ceviche mixto"
                            required
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3.5 text-gray-950 placeholder-gray-400 focus:border-gray-900 focus:ring-gray-900"
                        >

                    </div>


                    {{-- Categoría --}}
                    <div class="mt-5">

                        <label
                            for="categoria_id"
                            class="block text-sm font-bold text-gray-800"
                        >
                            Categoría
                        </label>

                        <select
                            name="categoria_id"
                            id="categoria_id"
                            required
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3.5 text-gray-950 focus:border-gray-900 focus:ring-gray-900"
                        >

                            <option value="">
                                Selecciona una categoría
                            </option>

                            @foreach ($categorias as $categoria)

                                <option
                                    value="{{ $categoria->id }}"
                                    @selected(old('categoria_id') == $categoria->id)
                                >
                                    {{ $categoria->nombre }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Precio --}}
                    <div class="mt-5">

                        <label
                            for="precio"
                            class="block text-sm font-bold text-gray-800"
                        >
                            Precio
                        </label>

                        <div class="relative mt-2">

                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-600">
                                S/
                            </span>

                            <input
                                type="number"
                                name="precio"
                                id="precio"
                                value="{{ old('precio') }}"
                                placeholder="0.00"
                                min="0"
                                step="0.01"
                                required
                                class="block w-full rounded-xl border-gray-300 bg-gray-50 py-3.5 pl-11 pr-4 text-gray-950 placeholder-gray-400 focus:border-gray-900 focus:ring-gray-900"
                            >

                        </div>

                    </div>


                    {{-- Estado --}}
                    <div class="mt-5">

                        <label class="flex items-center gap-3 cursor-pointer">

                            <input
                                type="checkbox"
                                name="activo"
                                value="1"
                                checked
                                class="h-5 w-5 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                            >

                            <div>

                                <p class="text-sm font-bold text-gray-800">
                                    Producto activo
                                </p>

                                <p class="text-xs text-gray-500">
                                    El producto podrá aparecer disponible para realizar pedidos.
                                </p>

                            </div>

                        </label>

                    </div>

                </div>


                {{-- Botones --}}
                <div class="flex flex-col-reverse sm:flex-row gap-3">

                    <a
                        href="{{ route('admin.productos.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3.5 rounded-xl border border-gray-300 bg-white text-gray-800 font-bold text-sm hover:bg-gray-50 active:scale-95 transition"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="w-full sm:flex-1 inline-flex items-center justify-center px-5 py-3.5 rounded-xl bg-gray-900 text-white font-bold text-sm shadow-sm hover:bg-gray-800 active:scale-95 transition"
                    >
                        Guardar producto
                    </button>

                </div>


            </form>

        </div>

    </div>

</x-app-layout>