<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @cspMetaTag
    <meta charset="utf-8">
    <title>Rema FIK</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!--SEO-->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Rema FIK | Universitas Al-Khairiyah">
    <meta property="og:description" content="Official repositori Fakultas Ilmu Komputer Universitas Al-Khairiyah.">
    <meta property="og:url" content="https://remafik.unival-cilegon.ac.id/">
    <meta property="og:site_name" content="Rema FIK">
    <meta property="og:image" content="{{ asset('assets/logo/favicon.webp') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Rema FIK | Universitas Al-Khairiyah">
    <meta name="twitter:description" content="Official repositori Fakultas Ilmu Komputer Universitas Al-Khairiyah.">
    <meta name="twitter:image" content="{{ asset('assets/logo/favicon.webp') }}">
    <!--SEO-->

    <link rel="shortcut icon" href="{{ asset('assets/logo/favicon.webp') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-6/assets/css/login-6.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/my.css') }}">
    <x-turnstile.scripts />
</head>

<body>
    <div class="auth-bg d-flex justify-content-center align-items-center">
        <div class="container">
            {{ $slot }}
        </div>
    </div>
</body>

</html>