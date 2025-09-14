<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="{{ config('app.name') }}">
        <meta name="description" content="Soluções em desenvolvimento, automações e integrações com Inteligência Artificial. Agende uma demonstração.">
        <meta name="keywords" content="sistemas, automações, inteligência artificial, tecnologia, desenvolvimento web, software personalizado, n8n, integração de sistemas, soluções tecnológicas">

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=roboto+mono:400,500,700&display=swap" rel="stylesheet" />

        <title>{{ $title ?? config('app.name') }}</title>

        <!-- AOS CSS for animations -->
        {{-- <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" /> --}}
        
        @livewireStyles
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- AOS Animation Library -->
        {{-- <script src="https://unpkg.com/aos@next/dist/aos.js"></script> --}}
    </head>
    <body class="font-sans antialiased">
        {{ $slot }}

        @livewireScriptConfig 
        <svg hidden class="hidden">@stack('bladeicons')</svg>
        @stack('scripts')
    </body>
</html>