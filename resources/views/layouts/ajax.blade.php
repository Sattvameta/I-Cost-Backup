<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title> @isset($title) {{ ($title . ' | ') }} @endisset {{ config('get.SYSTEM_APPLICATION_NAME') }}</title>

        @include('elements.styles')
        <!-- Push Page Styles  Balde -->
        @stack('styles')

        <style type="text/css">
            .content-wrapper{margin-left: 0px;}
        </style>

    </head>
    <body class="hold-transition">

        @include('components.login_header')


        @yield('content')


        @include('elements.scripts')


        @stack('scripts')

       
    </body>
</html>
