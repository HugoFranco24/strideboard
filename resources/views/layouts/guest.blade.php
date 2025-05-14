<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href=" {{ asset('css/guest.css') }} ">

        <link rel="icon" href="{{ asset('Images/Logos/Strideboard.png') }}" type="image/x-icon">

    </head>
    <body>
        <div class="main">
            <div class="img">
                <a href="/">
                    <img src="{{ asset('Images/Logos/StrideBoard.png') }}" height="80" width="80">
                </a>
            </div>

            <div class="box">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>