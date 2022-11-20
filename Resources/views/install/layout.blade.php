<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installer | OCMS</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <style>
        .wrapper {
            width: 679px;
            max-width: 95%;
            margin: auto;
            padding: .75rem 1.25rem;
            border-radius: 10px
        }

        .body-wrapper {
            height: 100vh;
            background-size: cover;
            display: flex;
            align-items: center;          
        }
    </style>
</head>

<body class="body-wrapper bg-light">
    <main class="wrapper">
        @yield('content')
        <footer class="container mx-auto pt-4">
            <div class="text-center">Made by <b>OMAICODE Team</b> with ❤</div>
        </footer>        
    </main>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
</body>

</html>
