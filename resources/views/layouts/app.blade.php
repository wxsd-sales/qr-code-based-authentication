<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
    <meta name="keywords" content="users,groups,webex,azure,sync,moderation">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Webex by Cisco</title>

    <!-- Preconnects -->
{{--    <link rel="preconnect" href="">--}}

<!-- Fonts -->
{{-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> --}}
{{-- <link href="" rel="stylesheet"> --}}

<!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('images/favicons/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('images/favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <style>
        @if(config('app.env') === 'local' && env('SHOW_OUTLINES'))
        * {
            outline: lightskyblue dashed 1px;
        }

        .container {
            outline: lightgreen dashed 4px;
        }

        .columns {
            outline: lightpink dashed 3px;
        }

        .column {
            outline: lightgrey dashed 2px;
        }
        @endif

        @if(env('PARTNER_LOGO_FILENAME'))
        .navbar-brand:before {
            content: '';
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0.2;
            background-image: url({{asset('images/' . env('PARTNER_LOGO_FILENAME'))}});
            background-size: contain;
            background-repeat: no-repeat;
            background-position: left center;
        }
        @endif

        @yield('css')
    </style>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('images/favicons/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('images/favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <link rel="shortcut icon" href="{{asset('images/favicons/favicon.ico')}}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="{{asset('images/favicons/browserconfig.xml')}}">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
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

        <noscript id="javascript-warning" class="hero is-danger is-bold">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">
                <span class="icon">
                  <i class="mdi mdi-alert"></i>
                </span>
                        <span>Javascript is disabled.</span>
                    </h1>
                    <h2 class="subtitle">
                        This site requires Javascript for its core functionality.
                        Please enable Javascript in browser settings and reload this page.
                    </h2>
                </div>
            </div>
        </noscript>

        <section id="cookies-warning" class="hero is-danger is-bold d-none">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">
                    <span class="icon">
                      <i class="mdi mdi-alert"></i>
                    </span>
                        <span>Cookies are disabled.</span>
                    </h1>
                    <h2 class="subtitle">
                        This site requires cookies for its core functionality.
                        Please enable cookies in browser settings and reload this page.
                    </h2>
                </div>
            </div>
        </section>

        <main id="app" class="py-4">
            @yield('content')
        </main>

        <footer class="section foot">
            <div class="container my-5">
                <p class="text-center">
                    <strong>{{ env('APP_NAME') }}</strong>
                    by <a href="https://github.com/WXSD-Sales">WXSD-Sales</a>.<br>
                    &copy; {{ date('Y') }} Webex by Cisco
                </p>
            </div>
        </footer>

        <!-- Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // https://github.com/Modernizr/Modernizr/blob/master/feature-detects/cookies.js
                function hasCookiesDisabled() {
                    // Quick test if browser has cookieEnabled host property
                    if (navigator.cookieEnabled) return false;
                    // Create cookie
                    document.cookie = "cookietest=1";
                    const isCookieSet = document.cookie.indexOf("cookietest=") !== -1;
                    // Delete cookie
                    document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";
                    return !isCookieSet;
                }

                if (hasCookiesDisabled()) {
                    console.log("Cookies are disabled.")
                    document.getElementById('cookies-warning').classList.remove('d-none')
                    document.getElementById('app').classList.add('d-none')
                }
            })
        </script>
        @if(config('app.env') === 'local')
            <script src="{{ mix('js/manifest.js') }}" defer></script>
            <script src="{{ mix('js/vendor.js') }}" defer></script>
        @endif
        <script src="{{ mix('js/app.js') }}" defer></script>
    @yield('js')
</body>
</html>
