<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ url('/images/favicon.ico') }}" type="image/x-icon">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('welcome') }}">
                    <h4>{{ config('app.name', 'Laravel') }}</h4>
                </a>
                @php
                    $seg = request()->segment(1, '');
                    $url = Request::fullUrl();

                    if (! $seg) {
                        $urlRu = $url.'/ru';
                        $urlEn = $url.'/en';
                    } elseif (in_array($seg, config('app.locales'))) {
                        $urlRu = str_replace($seg, 'ru', $url);
                        $urlEn = str_replace($seg, 'en', $url);
                    } else {
                        $urlRu = str_replace($seg, 'ru/'.$seg, $url);
                        $urlEn = str_replace($seg, 'en/'.$seg, $url);
                    }
                @endphp
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Language') }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{$urlRu}}">Русский</a>
                        <a class="dropdown-item" href="{{$urlEn}}">English</a>
                    </div>
                </div>
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
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <div class="d-flex">

                                @if(! isset($isSearch))
                                    <div class="pr-3">
                                        <a class="navbar-item" href="{{ route('welcome') }}">
                                            {{ __('Search') }}
                                        </a>
                                    </div>
                                @endif

                                @if(Auth::user()->admin)
                                    <div class="pr-3">
                                        <a class="navbar-item" href="{{ route('languages.index') }}">
                                            {{ __('Translations') }}
                                        </a>
                                    </div>
                                    <div class="pr-3">
                                        <a class="navbar-item" href="{{ route('admin.index') }}">
                                            {{ __('Users') }}
                                        </a>
                                    </div>
                                @endif

                                <div class="pr-3">
                                    <a class="navbar-item" href="{{ route('profile.edit') }}">
                                        {{ Auth::user()->name }} {{ Auth::user()->surname }}
                                    </a>
                                </div>

                                <div class="pr-3">
                                    <a class="navbar-item" href="{{ route('house.index') }}">{{ __('My accommodation') }}
                                        {{ Auth::user()->newInBooks() }}
                                    </a>
                                </div>

                                <div class="pr-3">
                                    <a class="navbar-item" href="{{ route('booking.index') }}">{{ __('Applications') }}
                                        {{ Auth::user()->unreadOutBooks() }}
                                    </a>
                                </div>

                                <div class="pr-3">
                                    <a class="navbar-item" href="{{ route('booking.history') }}">{{ __('History') }}</a>
                                </div>

                                <div>
                                    <a class="navbar-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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
            <div class="row">
                @yield('filters')
                @yield('content')
            </div>
        </main>

    </div>

    <footer class="text-center small py-3 bg-white">
        <div class="container">
            © {{ date('Y') }} Copyright: {{ config('app.name') }}
        </div>
    </footer>

</body>
</html>
