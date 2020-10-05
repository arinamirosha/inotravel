<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('welcome') }}">
                    <h4>{{ config('app.name', 'Laravel') }}</h4>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto" id="menu">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Вход</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                                </li>
                            @endif
                        @else
                            <div class="d-flex">


                                @if(Auth::user()->admin)
                                <div class="pr-3">
                                    <a class="navbar-item" href="{{ route('admin.index') }}">
                                        Пользователи
                                    </a>
                                </div>
                                @endif

                                <div class="pr-3">
                                    <a class="navbar-item" href="{{ route('profile.edit') }}">
                                        {{ Auth::user()->name }} {{ Auth::user()->surname }}
                                    </a>
                                </div>

                                <div class="pr-3">
                                    <a class="navbar-item" href="{{ route('house.index') }}">Мое жилье
                                        {{ Auth::user()->newInBooks() }}
                                    </a>
                                </div>

                                <div class="pr-3">
                                    <a class="navbar-item" href="{{ route('booking.index') }}">Заявки
                                        {{ Auth::user()->unreadOutBooks() }}
                                    </a>
                                </div>

                                <div>
                                    <a class="navbar-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Выход') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>

                            </div>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="text-center small bg-white py-3">
            <div class="container">
                © {{ date('Y') }} Copyright: {{ config('app.name') }}
            </div>
        </footer>

    </div>
</body>
</html>
