<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Подарки на праздники</title>


    <!-- Styles -->
    <script src="{{ asset('js/bootstrap.bundle.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3 px-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Сервис подарков
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Categories with celebrations -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="celebrationDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('messages.category_1') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="celebrationDropdown">
                            @foreach ($celebrations as $celebration)
                                <li>
                                    <a class="dropdown-item" href="{{ route('celebrations.show', ['celebration' => $celebration['id']]) }}">{{ $celebration['name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>


                <!-- Centered City Selector -->
                <livewire:city-selector/>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Language Selector -->
                    <livewire:language-switcher/>

                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('messages.Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('messages.Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('messages.Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
<footer class="text-center py-4 bg-light fixed-bottom">
    <div class="container">
        <p>&copy; {{ date('Y') }} Ваш проект. Все права защищены.</p>
    </div>
</footer>

@livewireScripts
</body>
</html>
