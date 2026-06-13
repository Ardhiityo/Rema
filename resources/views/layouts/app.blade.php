<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @cspMetaTag
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rema FIK</title>

    <!--SEO-->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Rema FIK | Universitas Al-Khairiyah">
    <meta property="og:description" content="Official Repositori Fakultas Ilmu Komputer Universitas Al-Khairiyah.">
    <meta property="og:url" content="https://remafik.unival-cilegon.ac.id/">
    <meta property="og:site_name" content="Rema FIK">
    <meta property="og:image" content="{{ asset('assets/logo/favicon.webp') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Rema FIK | Universitas Al-Khairiyah">
    <meta name="twitter:description" content="Official Repositori Fakultas Ilmu Komputer Universitas Al-Khairiyah.">
    <meta name="twitter:image" content="{{ asset('assets/logo/favicon.webp') }}">
    <!--SEO-->

    <link rel="shortcut icon" href="{{ asset('assets/logo/favicon.webp') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}" @cspNonce></script>

    <div id="app">
        <div id="sidebar">
            <x-sidebar />
        </div>
        <div id="main">
            <x-header />
            @yield('content')
            <x-footer />
        </div>
    </div>

    <script src="{{ asset('assets/static/js/components/dark.js') }}" @cspNonce></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}" @cspNonce></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}" @cspNonce></script>
    <!-- Need: Apexcharts -->
    @stack('scripts')
</body>

</html>