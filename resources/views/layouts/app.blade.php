<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! SEOMeta::generate() !!}
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Сервис подарков</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
    @livewireStyles
</head>
<body>
<div id="app" class="mb-5">
    <x-navbar></x-navbar>

    <main class="py-4">
        @yield('content')
    </main>
</div>
<x-footer></x-footer>

@livewireScripts
</body>
</html>
