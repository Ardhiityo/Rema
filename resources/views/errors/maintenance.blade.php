<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Maintenance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #1e3a8a, #2563eb, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .container {
            text-align: center;
            max-width: 500px;
            padding: 40px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .loader {
            margin: 20px auto;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .footer {
            font-size: 13px;
            opacity: 0.7;
            margin-top: 20px;
        }

        @media (max-width: 500px) {
            .container {
                margin: 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">🚧</div>
        <h1>Sedang Maintenance</h1>
        <p>Kami sedang melakukan peningkatan sistem.<br>
            Silakan hubungi Akademik FIK jika ada pertanyaan.</p>

        <div class="loader"></div>

        <div class="footer">
            &copy; Rema FIK - Fakultas Ilmu Komputer Universitas Al-Khairiyah
        </div>

        <div class="text-center mt-3">
            <small>
                <span class="footer">
                    Developed By
                </span>
                <a href="https://www.linkedin.com/in/ardhiityo" target="_blank" style="color: #fff;"
                    rel="noopener noreferrer">
                    <strong>Arya Adhi Prasetyo</strong>
                </a>
            </small>
        </div>
    </div>
</body>

</html>