<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="{{ asset('Images/Logos/Strideboard.png') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ asset('css/dashboard/general.css') }}">

        <title>@yield('title')</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: var(--background-color);
                color: var(--text-color);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;   
                height: 100vh;
                margin: 0;
            }

            .center {
                height: 100vh;
                align-items: center;
                display: flex;
                justify-content: center;
                position: relative;
                text-align: center;
            }

            h1{
                font-size: 5vw;
                margin-top: 0;
            }
            h2{
                font-size: 1.5vw;
            }
            .img{
                width: 60vw;
                height: 40vh;
            }
        </style>

        <script>
            const savedTheme = localStorage.getItem('theme') || 'system';
            const root = document.documentElement;

            if (savedTheme === 'dark') {
                root.classList.add('dark_theme');
                console.log('teste');
            } else if (savedTheme === 'light') {
                root.classList.remove('dark_theme');
            } else if (savedTheme === 'system') {
                const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                root.classList.toggle('dark_theme', isDarkMode);
            }
        </script>
    </head>
    <body>
        <div class="center">
            <div class="content">
                <div class="error">
                    <h1>OOPS!!</h1>
                </div>
                <div class="img">
                    @yield('img')
                </div>
                <div class="desc">
                    @yield('desc')
                    <button onclick="history.back()" class="btn_default">Go Back</button>
                </div>
            </div>
        </div>
    </body>
</html>

