<x-guest-layout>

    <div class="mb-8">

        <p
            class="
                text-sm
                font-semibold
                uppercase
                tracking-[0.18em]
                text-slate-400
            "
        >
            Bienvenido
        </p>

        <h2
            class="
                mt-2
                text-3xl
                font-extrabold
                tracking-tight
                text-slate-950
            "
        >
            Iniciar sesión
        </h2>

        <p
            class="
                mt-2
                text-sm
                leading-6
                text-slate-500
            "
        >
            Ingresa con tu cuenta para acceder al sistema.
        </p>

    </div>


    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />


    <form
        method="POST"
        action="{{ route('login') }}"
        class="space-y-5"
    >

        @csrf


        <div>

            <label
                for="email"
                class="
                    mb-2
                    block
                    text-sm
                    font-semibold
                    text-slate-700
                "
            >
                Correo electrónico
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="correo@ejemplo.com"
                class="
                    block
                    min-h-12
                    w-full
                    rounded-xl
                    border
                    border-slate-300
                    bg-white
                    px-4
                    py-3
                    text-base
                    text-slate-900
                    outline-none
                    transition
                    placeholder:text-slate-400
                    focus:border-slate-950
                    focus:ring-2
                    focus:ring-slate-950/10
                "
            >

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>


        <div>

            <label
                for="password"
                class="
                    mb-2
                    block
                    text-sm
                    font-semibold
                    text-slate-700
                "
            >
                Contraseña
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Ingresa tu contraseña"
                class="
                    block
                    min-h-12
                    w-full
                    rounded-xl
                    border
                    border-slate-300
                    bg-white
                    px-4
                    py-3
                    text-base
                    text-slate-900
                    outline-none
                    transition
                    placeholder:text-slate-400
                    focus:border-slate-950
                    focus:ring-2
                    focus:ring-slate-950/10
                "
            >

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />

        </div>


        <div
            class="
                flex
                flex-wrap
                items-center
                justify-between
                gap-3
            "
        >

            <label
                class="
                    flex
                    items-center
                    gap-2
                    text-sm
                    text-slate-600
                "
            >

                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="
                        h-4
                        w-4
                        rounded
                        border-slate-300
                        text-slate-950
                        focus:ring-slate-950
                    "
                >

                Recordarme

            </label>


            @if (Route::has('password.request'))

                <a
                    href="{{ route('password.request') }}"
                    class="
                        text-sm
                        font-semibold
                        text-slate-700
                        transition
                        hover:text-slate-950
                    "
                >
                    ¿Olvidaste tu contraseña?
                </a>

            @endif

        </div>


        <button
            type="submit"
            class="
                flex
                min-h-12
                w-full
                items-center
                justify-center
                rounded-xl
                bg-slate-950
                px-5
                py-3
                text-base
                font-bold
                text-white
                transition
                hover:bg-slate-800
                focus:outline-none
                focus:ring-2
                focus:ring-slate-950
                focus:ring-offset-2
                active:scale-[0.99]
            "
        >
            Ingresar al sistema
        </button>

    </form>

</x-guest-layout>