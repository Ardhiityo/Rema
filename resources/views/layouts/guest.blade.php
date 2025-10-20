<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Rema</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link rel="shortcut icon" href="{{ asset('assets/logo/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-6/assets/css/login-6.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div style="min-height: 100dvh; background-image: url({{ asset('assets/background/6017669.jpg') }}); background-size: cover;"
        class="d-flex justify-content-center align-items-center">
        <div class="container">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
