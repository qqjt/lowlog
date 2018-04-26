<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta')
    <title>@yield('title', config('app.name'))</title>

    <!-- Styles -->
    <link href="{{ cdn(mix('css/app.css')) }}" rel="stylesheet">
    <link href="{{ cdn(mix('vendor/sweetalert/sweetalert.min.css')) }}" rel="stylesheet">
    @yield('style')
</head>
<body>
<div id="app" class="pt-5">
    <nav class="navbar navbar-expand-md navbar-light bg-light box-shadow fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li>
                        <a class="nav-link" href="{{ route('archive') }}"><i
                                    class="fa fa-archive"></i>&nbsp;{{__("Archive")}}
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('about') }}"><i class="fa fa-user"></i>&nbsp;{{__("About")}}
                        </a>
                    </li>
                    <li>
                        <form method="get" action="{{route('post.search')}}" class="form-inline my-2 my-md-0">
                            <input name="q" class="form-control" type="text" placeholder="{{_("Search")}}">
                        </form>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Authentication Links -->
                    @guest
                        <li><a class="nav-link" href="{{ route('login') }}">{{__("Login")}}</a></li>
                        <li><a class="nav-link" href="{{ route('register') }}">{{__("Register")}}</a></li>
                    @else
                        <li><a class="nav-link" href="{{ route('post.create') }}"><i
                                        class="fa fa-plus"></i>&nbsp;{{__("New")}}</a></li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{__("Logout")}}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
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

    <footer class="py-3">
        <div class="container">
            <p class="m-0 text-center"><i class="fa fa-copyright"></i> low.bi 2018 | <i class="fa fa-github"></i> <a href="https://github.com/qqjt/lowlog">lowlog</a></p>
        </div>
        <!-- /.container -->
    </footer>
</div>

<!-- Scripts -->
<script src="{{ cdn(mix('js/manifest.js')) }}"></script>
<script src="{{ cdn(mix('js/vendor.js')) }}"></script>
<script src="{{ cdn(mix('js/app.js')) }}"></script>
<script src="{{ cdn(mix('/vendor/sweetalert/sweetalert.min.js'))}}"></script>
<script src="https://use.fontawesome.com/b85589c87e.js"></script>
@yield('script')
</body>
</html>
