<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                Editar categoría
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Modifica la información de la categoría.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mensajes de validación --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="font-semibold text-red-800 mb-2">
                        Revisa los siguientes errores:
                    </p>

                    <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulario --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-950">
                        Editar categoría
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Actualiza los datos de "{{ $categoria->nombre }}".
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.categorias.update', $categoria) }}"
                    class="space-y-6"
                >

                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div>
                        <label
                            for="nombre"
                            class="block text-sm font-bold text-gray-800 mb-2"
                        >
                            Nombre de la categoría
                        </label>

                        <input
                            id="nombre"
                            type="text"
                            name="nombre"
                            value="{{ old('nombre', $categoria->nombre) }}"
                            required
                            autofocus
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                            placeholder="Ejemplo: Bebidas"
                        >

                        @error('nombre')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div class="flex items-center justify-between gap-4 p-4 bg-gray-50 rounded-xl">

                        <div>
                            <p class="text-sm font-bold text-gray-900">
                                Estado de la categoría
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Las categorías activas pueden utilizarse en los productos.
                            </p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">

                            <input
                                type="checkbox"
                                name="activo"
                                value="1"
                                class="sr-only peer"
                                {{ old('activo', $categoria->activo) ? 'checked' : '' }}
                            >

                            <div class="w-11 h-6 bg-gray-300 rounded-full peer
                                peer-checked:bg-gray-900
                                peer-focus:ring-2 peer-focus:ring-gray-300
                                after:content-['']
                                after:absolute
                                after:top-[2px]
                                after:left-[2px]
                                after:bg-white
                                after:rounded-full
                                after:h-5
                                after:w-5
                                after:transition-all
                                peer-checked:after:translate-x-full">
                            </div>

                        </label>

                    </div>

                    {{-- Botones --}}
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">

                        <a
                            href="{{ route('admin.categorias.index') }}"
                            class="inline-flex items-center justify-center px-5 py-3 rounded-xl
                            bg-gray-100 text-gray-700 font-bold text-sm
                            hover:bg-gray-200 transition"
                        >
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-5 py-3 rounded-xl
                            bg-gray-900 text-white font-bold text-sm
                            hover:bg-gray-700 transition"
                        >
                            Guardar cambios
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>