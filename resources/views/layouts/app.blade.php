<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token" />

    <title>{{ config('app.name', 'Online store') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{--<script src="https://code.jquery.com/jquery-3.6.0.js"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/js/online_store_token_globalize.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/online_store_main.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-lg" style="height: 5vh !important; background: url(/back_gray_min.jpg) repeat">
            <div class="container-fluid font-weight-bold">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Online store') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuStore" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Склад
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuStore" style="background: url(/back_gray_min.jpg) repeat">
                                <a class="dropdown-item" href="{{ route('warehouses.index') }}"><b>{{ __('Склады') }}</b></a>
                                <a class="dropdown-item" href="{{ route('goods.index') }}"><b>{{ __('Товары') }}</b></a>
                                <a class="dropdown-item" href="{{ route('categories.index') }}"><b>{{ __('Категории') }}</b></a>
                                <a class="dropdown-item" href="{{ route('additionalChars.index') }}"><b>{{ __('Дополнительные характеристики') }}</b></a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuManagering" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Управление
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuManagering" style="background: url(/back_gray_min.jpg) repeat">
                                <a class="dropdown-item" href="{{ route('orders.index', ['sort[id]=desc']) }}"><b>{{ __('Заказы') }}</b></a>
                            </div>
                        </li>
                        {{--<li class="nav-item">
                            <a href="{{ route('utils.regenerateDb') }}" style="text-decoration: none" onclick="return confirm('Вы действительно хотите это сделать?\nВсе текущие данные будут уничтожены и заполнены случайно сгенерированными данными.\nПроцесс может занять значительное время до нескольких минут.')">
                                <button class="btn btn-outline-danger collapse regen_btn show" type="button" id="regen_btn1" data-toggle="collapse" data-target=".regen_btn" aria-controls="regen_btn1 regen_btn2">
                                    &#9851;Перегенерировать базу
                                </button>
                            </a>
                            <button class="btn btn-outline-danger collapse regen_btn" type="button" id="regen_btn2" data-toggle="collapse" data-target=".regen_btn" aria-controls="regen_btn1 regen_btn2">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Перегенерировать базу
                            </button>
                        </li>--}}
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" style="background: url(/back_gray_min.jpg) repeat" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        {{ __('Выход') }}
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
        <div style="height: 95vh !important">
            @yield('content')
        </div>
    </div>
</body>
</html>
