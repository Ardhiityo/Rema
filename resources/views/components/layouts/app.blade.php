<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rema FIK</title>

    <link rel="shortcut icon" href="{{ asset('assets/logo/favicon.webp') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}" @cspNonce></script>

    <div id="app" style="min-height: 100dvh">
        <div id="sidebar">
            <x-sidebar />
        </div>
        <div id="main" class="d-flex flex-column justify-content-between">
            <section>
                <x-header />
                {{ $slot }}
            </section>
            <x-footer />
        </div>
    </div>

    <script src="{{ asset('assets/static/js/components/dark.js') }}" @cspNonce></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}" @cspNonce></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}" @cspNonce></script>

    @stack('scripts')
</body>

</html>