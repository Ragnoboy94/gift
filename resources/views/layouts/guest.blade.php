<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Сервис подарков</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @livewireScripts
    </head>
    <body>
    <div class="font-sans text-gray-900 antialiased mb-5">
            {{ $slot }}
        </div>
    </body>
</html>
