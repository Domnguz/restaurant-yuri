<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Restaurant Yuri') }} - Mesero
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8f7f4] text-gray-950 antialiased">

    <div class="min-h-screen">

        @include('layouts.mesero-navigation')

        <main class="pb-24">

            {{ $slot }}

        </main>

    </div>

</body>

</html>