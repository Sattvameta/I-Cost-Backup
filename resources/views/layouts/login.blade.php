<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @isset($title) {{ ($title . ' | ') }} @endisset {{ config('get.SYSTEM_APPLICATION_NAME') }}</title>

    @include('elements.login_styles')
    <!-- Push Page Styles  Balde -->
    @stack('styles')

</head>
<body>
        <div class="app-container app-theme-white body-tabs-shadow">
            <div class="app-container">
       
        @yield('content')
   </div>
        </div>
    @include('elements.login_scripts')
    @stack('scripts')
</body>
</html>