<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App</title>
    <link href="{{ asset('js/assets/auth.css') }}" rel="stylesheet">
    {{--    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/x-icon">--}}
</head>
<body id="root" class="min-h-[100vh]">
<!-- Contenido de la página -->

<!-- Incluir el archivo main.js compilado -->
<script type="module" src="{{ asset('js/app/index.js') }}"></script>
</body>
</html>
