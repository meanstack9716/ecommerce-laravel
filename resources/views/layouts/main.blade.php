<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @yield('title')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional head content -->
    @stack('head')
</head>
<?php 
    $user = auth()->user() ?? null;
?>
<body class="">
    <div class="flex bg-zinc-100 min-h-screen gap-6">
        @include('partials.header')

        <div id="sidebar" class="w-72 3xl:w-80 h-screen fixed duration-500 pt-18 3xl:pt-20 hidden lg:block">
            <x-side-bar />
        </div>

        <div class="duration-500 pt-18 3xl:pt-20 lg:pl-72 3xl:pl-80 w-full" id="main">
            @yield('content')

            @include('partials.footer')
        </div>
    </div>

    @stack('scripts')
    <script>
        window.sanctumToken = '{{ session('sanctum_token') }}';
    </script>
    <script>
        const toggleSideBarBtn = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');

        toggleSideBarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');

            main.classList.toggle('lg:pl-72');
            main.classList.toggle('3xl:pl-80');
            main.classList.toggle('lg:pl-0');
        });
    </script>
</body>
</html>