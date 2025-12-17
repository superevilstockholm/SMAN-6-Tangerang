<!DOCTYPE html>
<html lang="en">

<head>
    {{-- Theme --}}
    <script>
        function applyTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
        }
        function applyThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            if (!icon) return;
            icon.classList.remove('ti-sun', 'ti-moon');
            icon.classList.add(theme === 'light' ? 'ti-sun' : 'ti-moon');
        }
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const theme = (savedTheme === 'dark' || savedTheme === 'light') ? savedTheme : 'light';
            applyTheme(theme);
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- SEO --}}
    <meta name="description" content="@yield('meta-description')">
    <meta name="keywords" content="@yield('meta-keywords')">
    <title>@yield('title', 'SMAN 6 Tangerang')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('static/css/bootstrap.min.css') }}">
    {{-- Additional CSS --}}
    @stack('css')
</head>

<body>
    @yield('layout')
    {{-- Bootstrap --}}
    <script src="{{ asset('static/js/bootstrap.bundle.min.js') }}"></script>
    {{-- Additional JS --}}
    @stack('js')
</body>

</html>
