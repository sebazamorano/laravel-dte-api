<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#000000" />
    <link
        rel="apple-touch-icon"
        sizes="76x76"
        href="/img/apple-icon.png"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"
    />
    <link
        rel="stylesheet"
        href="/vendor/@fortawesome/fontawesome-free/css/all.min.css"
    />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/creativetimofficial/tailwind-starter-kit/compiled-tailwind.min.css"
    />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">


    <script src="{{ mix('/js/manifest.js')}}" defer></script>
    <script src="{{ mix('/js/vendor.js') }}" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    @stack('custom-styles')
    @hasSection('title')
        <title>@yield('title') - {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif
</head>

<body class="text-gray-800 antialiased">

    <div id="app">
        @include('layouts.sidebar')

        <div class="relative md:ml-64 bg-gray-100">
            @include('layouts.navbar')
            @yield('content')
        </div>
    </div>


    <script type="text/javascript">
        window.addEventListener('swal',function(e){
            Swal.fire(e.detail);
        });
    </script>

    @include('layouts.scripts')
    @stack('custom-scripts')
</body>
</html>
