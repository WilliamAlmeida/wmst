<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- SEO server-side (visível a crawlers sem JS). Ver App\Support\Seo. --}}
        @php($seo = data_get($page, 'props.seo', []))
        @if(! empty($seo))
            <meta name="description" content="{{ $seo['description'] ?? '' }}">
            <link rel="canonical" href="{{ $seo['url'] ?? url()->current() }}">
            <meta property="og:type" content="{{ $seo['type'] ?? 'website' }}">
            <meta property="og:site_name" content="{{ $seo['siteName'] ?? 'WMST' }}">
            <meta property="og:title" content="{{ $seo['title'] ?? '' }}">
            <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
            <meta property="og:url" content="{{ $seo['url'] ?? url()->current() }}">
            <meta property="og:image" content="{{ $seo['image'] ?? '' }}">
            <meta property="og:image:width" content="{{ $seo['imageWidth'] ?? 1200 }}">
            <meta property="og:image:height" content="{{ $seo['imageHeight'] ?? 630 }}">
            @if(($seo['type'] ?? null) === 'article' && ! empty($seo['publishedTime']))
                <meta property="article:published_time" content="{{ $seo['publishedTime'] }}">
            @endif
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="{{ $seo['title'] ?? '' }}">
            <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
            <meta name="twitter:image" content="{{ $seo['image'] ?? '' }}">
            @foreach(($seo['schemas'] ?? []) as $schema)
                <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
            @endforeach
        @endif

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
        <link rel="icon" type="image/png" sizes="512x512" href="/favicon-512.png">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        {{-- PWA --}}
        <link rel="manifest" href="/manifest.webmanifest">
        <meta name="theme-color" content="#16233f">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="WMST">
        <meta name="application-name" content="WMST">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ $seo['title'] ?? config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
