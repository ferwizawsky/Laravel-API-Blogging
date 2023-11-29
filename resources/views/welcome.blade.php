<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="app-name" content="{{ ENV('APP_NAME') }}">
    <!-- <meta name="description" content="{{ ENV('APP_DESC') }}"> -->
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* @font-face {
            font-family: ethno;
            src: url("/ethnocentric.otf");
        }
        .ethno {
            font-family: ethno;
        } */
    </style>
    @vite('resources/css/app.css')
</head>

<body>
    <div id="app">
    </div>
    @vite('resources/js/app.js')
</body>

</html>