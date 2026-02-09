<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Event Management') }}</title>

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />

        <!-- Bootstrap CSS from Modernize Template -->
        <link rel="stylesheet" href="{{ asset('assets/styles.min.css') }}" />

        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @inertiaHead
    </head>
    <body>
        @inertia

        <!-- Bootstrap JS and dependencies -->
        <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
        <script src="{{ asset('assets/js/app.min.js') }}"></script>
    </body>
</html>
