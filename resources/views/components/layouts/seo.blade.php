@props([
	'seo' => [
		'app_name' => config('app.name'),
		'manifest_v' => config('custom.manifest_version'),
	],
])
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Soluções em desenvolvimento, automações e integrações com Inteligência Artificial. Agende uma demonstração.">
		<meta name="keywords" content="sistemas, automações, inteligência artificial, tecnologia, desenvolvimento web, software personalizado, n8n, integração de sistemas, soluções tecnológicas">
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

		<!-- Web Application Manifest -->
		<link rel="manifest" href="{{ asset('manifest.json') }}">

		<!-- Chrome for Android theme color -->
		<meta name="theme-color" content="#000000">
		<meta name="msapplication-starturl" content="{{ route('home') }}">

		<!-- Add to homescreen for Chrome on Android -->
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="application-name" content="{{ $seo['app_name'] }}">
		<link href="{{ asset('icons/icon-192x192-'.$seo['manifest_v'].'.jpg') }}" rel="icon" sizes="192x192">

		<!-- Add to homescreen for Safari on iOS -->
		<meta name="apple-mobile-web-app-status-bar-style" content="#000000">
		<meta name="apple-mobile-web-app-title" content="{{ $seo['app_name'] }}">

		<link href="{{ asset('icons/icon-512x512-'.$seo['manifest_v'].'.jpg') }}" rel="apple-touch-icon">

		<link href="{{ asset('icons/splash-320x568-'.$seo['manifest_v'].'.jpg') }}" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

		<!-- iPhone X (1125px x 2436px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="{{ asset('icons/splash-1125x2436-'.$seo['manifest_v'].'.jpg') }}">
		<!-- iPhone 8, 7, 6s, 6 (750px x 1334px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="{{ asset('icons/splash-750x1334-'.$seo['manifest_v'].'.jpg') }}">
		<!-- iPhone 8 Plus, 7 Plus, 6s Plus, 6 Plus (1242px x 2208px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" href="{{ asset('icons/splash-1242x2208-'.$seo['manifest_v'].'.jpg') }}">
		<!-- iPhone 5 (640px x 1136px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" href="{{ asset('icons/splash-640x1136-'.$seo['manifest_v'].'.jpg') }}">
		<!-- iPad Mini, Air (1536px x 2048px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" href="{{ asset('icons/splash-1536x2048-'.$seo['manifest_v'].'.jpg') }}">
		<!-- iPad Pro 10.5" (1668px x 2224px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" href="{{ asset('icons/splash-1668x2224-'.$seo['manifest_v'].'.jpg') }}">
		<!-- iPad Pro 12.9" (2048px x 2732px) -->
		<link rel="apple-touch-startup-image" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" href="{{ asset('icons/splash-2048x2732-'.$seo['manifest_v'].'.jpg') }}">

		<meta name="apple-mobile-web-app-capable" content="yes">

		<!-- Tile for Win8 -->
		<meta name="msapplication-TileColor" content="#000000">
		<meta name="msapplication-TileImage" content="{{ asset('icons/icon-512x512-'.$seo['manifest_v'].'.jpg') }}">

		<!-- TWITTER SHARE -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:description" content="{{ $seo['app_name'] }}, garantindo sua segurança.">
		<meta name="twitter:title" content="{{ $seo['app_name'] }}">
		<meta name="twitter:site" content="@zcops">
		<meta name="twitter:domain" content="{{ $seo['app_name'] }}">
		<meta name="twitter:creator" content="@zcops">

		<!-- FACEBOOK SHARE -->
		<meta property="og:locale"              content="pt_BR" />
		<meta property="og:url"                 content="{{ route('home') }}" />
		<meta property="og:title"               content="{{ $seo['app_name'] }}" />
		<meta property="og:site_name"           content="{{ $seo['app_name'] }}" />
		<meta property="og:description"         content="Especialistas em segurança privada há mais de 21 anos." />
		<meta property="og:image"    			content="{{ asset('icons/icon-384x384-'.$seo['manifest_v'].'.jpg') }}" itemprop="image" />
		<meta property="og:image:alt"       	content="{{ $seo['app_name'] }}" />
		<meta property="og:image:type"			content="image/png" />
		<meta property="og:image:width" 		content="201" />
		<meta property="og:image:height" 		content="160" />
		<meta property="og:image:type"			content="image/png" />
		<meta property="og:type" 				content="website" />
		<meta property="article:author" 		content="{{ $seo['app_name'] }}">
		<meta property="article:section" 		content="Segurança">
		<meta property="article:tag" 			content="{{ $seo['app_name'] }}">
		<meta property="article:published_time" content="26/08/2025">
		{{-- <meta property="fb:app_id"         		content="273921101283507" /> 	 --}}