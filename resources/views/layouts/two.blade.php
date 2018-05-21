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
    <link href="{{ cdn(mix('/css/app.css')) }}" rel="stylesheet">
    <link href="{{cdn(mix('/css/custom.min.css'))}}" rel="stylesheet">
    <link href="{{ cdn(mix('/vendor/sweetalert/sweetalert.min.css')) }}" rel="stylesheet">
    <link rel="stylesheet" href="{{cdn(mix('/vendor/font-awesome/css/font-awesome.min.css'))}}">
    @yield('style')
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark flex-column flex-md-row bd-navbar bg-primary">
    <div class="container-fluid">
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
                <li class="nav-item {{active_class(if_route('archive'))}}">
                    <a class="nav-link" href="{{ route('archive') }}"><i
                                class="fa fa-archive"></i>&nbsp;{{__("Archive")}}
                    </a>
                </li>
                <li class="nav-item {{active_class(if_route('about'))}}">
                    <a class="nav-link" href="{{ route('about') }}"><i class="fa fa-user"></i>&nbsp;{{__("About")}}
                    </a>
                </li>
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="{{active_class(if_route('login'))}}"><a class="nav-link" href="{{ route('login') }}"><i
                                    class="fa fa-sign-in"
                                    aria-hidden="true"></i>&nbsp;{{__("Login")}}
                        </a></li>
                    <li class="{{active_class(if_route('register'))}}"><a class="nav-link"
                                                                          href="{{ route('register') }}"><i
                                    class="fa fa-user-plus"
                                    aria-hidden="true"></i>&nbsp;{{__("Register")}}
                        </a></li>
                @else
                    @can('create', \App\Post::class)
                        <li class="{{active_class(if_route('post.create'))}}"><a class="nav-link"
                                                                                 href="{{ route('post.create') }}"><i
                                        class="fa fa-plus"></i>&nbsp;{{__("New")}}</a></li>
                    @endcan
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

<div class="container-fluid">
    <div class="row flex-xl-nowrap">
        <div class="col-12 col-md-3 col-xl-3 bd-sidebar">
            <form method="get" action="{{route('post.search')}}" class="bd-search d-flex align-items-center">
                <div class="input-group">
                    <input type="text" class="form-control ds-input" placeholder="{{__("Search")}}"
                           aria-label="{{__("Search")}}" name="q" value="{{request('q')}}">
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-outline-primary" type="submit"><i
                                    class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
            @yield("sidebar")
        </div>
        <main class="col-12 col-md-9 col-xl-9 py-md-3 pl-md-5 bd-content" role="main">
            @yield("content")
            <footer class="py-3">
                <div class="container">
                    <p class="m-0 text-center"><i class="fa fa-copyright"></i> low.bi 2018 | <i
                                class="fa fa-github"></i> <a
                                href="https://github.com/qqjt/lowlog">lowlog</a></p>
                </div>
            </footer>
        </main>
    </div>
</div>
<!-- Scripts -->
<script src="{{ cdn(mix('/js/manifest.js')) }}"></script>
<script src="{{ cdn(mix('/js/vendor.js')) }}"></script>
<script src="{{ cdn(mix('/js/app.js')) }}"></script>
<script src="{{ cdn(mix('/vendor/sweetalert/sweetalert.min.js'))}}"></script>
@yield('script')
</body>
</html>
