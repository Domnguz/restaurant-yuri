<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restaurant Yuri') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8f7f4] text-gray-950 antialiased">

    <div class="min-h-screen">

        @include('layouts.navigation')

        <main class="pb-24">

            @isset($header)

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">

                    <div class="text-gray-950">
                        {{ $header }}
                    </div>

                </div>

            @endisset

            {{ $slot }}

        </main>

    </div>

</body>

</html>