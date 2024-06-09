<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/partials/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="content-wrapper">
        <!-- Header and other content -->
        <header>
           
        </header>
        <main>
            @yield('content')
        </main>
        <footer class="footer">
            <a href="/contact" class="contact-button">Kontakt</a>
            <p class="text">© 2024 Programming</p>
        </footer>
    </div>
</body>
</html>
