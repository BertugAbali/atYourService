<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
      a:link { text-decoration: none; color: black;}


a:visited { text-decoration: none;  color: black;}


a:hover { text-decoration: none; color: aqua;}


a:active { text-decoration: none;  color: black;}
    </style>


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>At Your Service</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        @include('includes.header')
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
@include('includes.footer')

</html>