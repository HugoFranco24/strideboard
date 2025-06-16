<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        document.documentElement.classList.add('preload');

        function toggleMenu() {
            const menu = document.getElementById("nav");
            const img = document.getElementById("toogleM");
            const close = document.getElementById("close");
            const open = document.getElementById("open");
            const icons8 = document.getElementById("icons8");
            const links = document.getElementById("links");
            const top_menu = document.getElementById("top-menu");
            const main = document.getElementById("main_content");
            const sepC = document.getElementById("sepC");

            let newState;
            if (menu.style.width === "265px") {
                menu.style.width = "68px";
                menu.style.transition = "500ms";
                img.style.left = "8px";
                img.style.transition = "500ms";
                open.style.opacity = "1";
                close.style.opacity = "0";
                icons8.style.display = "none";
                links.classList.add('links_colapse');
                top_menu.style.margin = "0px 0px 0px 68px";
                top_menu.style.transition = "500ms";
                main.style.padding = "110px 30px 30px 104px";
                main.style.transition = "500ms";
                sepC.style.visibility = "visible";
                newState = 'collapsed';
            } else {
                menu.style.width = "265px";
                menu.style.transition = "500ms";
                img.style.left = "198px";
                img.style.transition = "500ms";
                open.style.opacity = "0";
                close.style.opacity = "1";
                icons8.style.display = "block";
                links.classList.remove('links_colapse');
                top_menu.style.margin = "0px 0px 0px 265px";
                top_menu.style.transition = "500ms";
                main.style.padding = "110px 30px 30px 296px";
                main.style.transition = "500ms";
                sepC.style.visibility = "hidden";
                newState = 'not_collapsed';
            }
            
        }

        function toggleProfile() {
            var profile_toggle = document.getElementById('profile-toggle');
            var arrowP = document.getElementById('arrowP');

            if (profile_toggle.style.top == '-90px') {
                profile_toggle.style.top = '90px';
                arrowP.classList.add('rotated');
            } else {
                profile_toggle.style.top = '-90px';
                arrowP.classList.remove('rotated');
            }
        }
        
        function applyMenuState(state) {
            const menu = document.getElementById("nav");
            const img = document.getElementById("toogleM");
            const close = document.getElementById("close");
            const open = document.getElementById("open");
            const icons8 = document.getElementById("icons8");
            const links = document.getElementById("links");
            const top_menu = document.getElementById("top-menu");
            const main = document.getElementById("main_content");
            const sepC = document.getElementById("sepC");


            if (window.innerWidth <= 900) {
                state = 'collapsed';
                img.style.display = "none";
            }else{
                img.style.display = "block";
            }

            if (state === 'collapsed') {
                menu.style.width = "68px";
                img.style.left = "8px";
                open.style.opacity = "1";
                close.style.opacity = "0";
                icons8.style.display = "none";
                links.classList.add('links_colapse');
                top_menu.style.margin = "0px 0px 0px 68px";
                main.style.padding = "110px 30px 30px 104px";
                sepC.style.visibility = "visible";
            } else {
                menu.style.width = "265px";
                img.style.left = "198px";
                open.style.opacity = "0";
                close.style.opacity = "1";
                icons8.style.display = "block";
                links.classList.remove('links_colapse');
                top_menu.style.margin = "0px 0px 0px 265px";
                main.style.padding = "110px 30px 30px 296px";
                sepC.style.visibility = "hidden";
            }
        }

        function applyTheme(theme) {
            const root = document.documentElement;
            if (theme === 'dark') {
                root.classList.add('dark_theme');
            } else if (theme === 'light') {
                root.classList.remove('dark_theme');
            } else if (theme === 'system') {
                const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                root.classList.toggle('dark_theme', isDarkMode);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {

            window.addEventListener('resize', () => {  //para checkar se esta abaixo de 900px a window
                const savedMenuState = localStorage.getItem('menuState') || 'not_collapsed';
                applyMenuState(savedMenuState);
            });

            const savedTheme = localStorage.getItem('theme') || 'system';

            const selectedThemeRadio = document.getElementById(savedTheme);
            if (selectedThemeRadio) selectedThemeRadio.checked = true;


            document.querySelectorAll('input[name="theme"]').forEach(radio => {
                radio.addEventListener('change', (e) => {
                const selectedTheme = e.target.id;
                applyTheme(selectedTheme);
                localStorage.setItem('theme', selectedTheme);
                });
            });
            

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (localStorage.getItem('theme') === 'system') {
                    applyTheme('system');
                }
            });

            const savedMenuState = localStorage.getItem('menuState') || 'not_collapsed';
            applyMenuState(savedMenuState);

            const selectedMenuRadio = document.getElementById(savedMenuState);
            if (selectedMenuRadio) selectedMenuRadio.checked = true;

            document.querySelectorAll('input[name="menuState"]').forEach((radio) => {
                radio.addEventListener('change', (e) => {
                    const selectedMenuState = e.target.id;
                    applyMenuState(selectedMenuState);
                    localStorage.setItem('menuState', selectedMenuState);
                });
            });

            const savedTheme1 = localStorage.getItem('theme') || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = savedTheme1 === 'dark' || (savedTheme1 === 'system' && prefersDark);
            if (isDark) {
                document.documentElement.classList.add('dark_theme');
            }

            window.addEventListener('load', () => {
                document.documentElement.classList.remove('preload');
            });
        });
    </script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta
        name="description"
        content="Projeto de Aptidão Profissional | Hugo Carreira Franco 3ºC Nº7 | Ano Letivo 2024/2025 | Escola Secundária Pinhal do Rei"
    >
    <title>@yield('title') | StrideBoard</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard/global/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/global/general.css') }}">
    @yield('css')

    <link rel="icon" href="{{ asset('Images/Logos/StrideBoard.png') }}" type="image/x-icon">

    @yield('custom_links')

    @yield('custom_js')
</head>

<body>
    <div class="hamburger" id="toogleM" onclick="toggleMenu()">
        <img src="{{ asset('Images/Icons/Menu/Left-arrow.png') }}" alt="" width="35" height="35" id="close">
        <img src="{{ asset('Images/Icons/Menu/Right-arrow.png') }}" alt="" width="35" height="35" id="open">
    </div>

    <nav id="nav" style="width: 265px;">
        <div class="links" id="links">
            <span class="sep">Manage</span>
            <a href="/dashboard" title="Dashboard"><img src="{{ asset('Images/Icons/Menu/Dashboard.png') }}" alt=""><span>Dashboard</span></a>
            <a href="/dashboard/projects" title="Projects"><img src="{{ asset('Images/Icons/Menu/Projects.png') }}" alt=""><span>Projects</span></a>
            <a href="/dashboard/tasks" title="Tasks"><img src="{{ asset('Images/Icons/Menu/Tasks.png') }}" alt=""/><span>Tasks</span></a>
            <a href="/dashboard/calendar" title="Calendar"><img src="{{ asset('Images/Icons/Menu/Calendar.png') }}" alt=""><span>Calendar</span></a>
            <div class="sepC" id="sepC"></div>
            <span class="sep">Settings</span>
            <a href="/dashboard/inbox" title="Inbox"><img src="{{ asset('Images/Icons/Menu/Inbox.png') }}" alt="" /><span>Inbox</span></a>
            <a href="/dashboard/my-profile" title="Profile"><img src="{{ asset('Images/Icons/Menu/Profile.png') }}" alt=""/><span>My Profile</span></a>
            <a href="/dashboard/settings" title="Settings"><img src="{{ asset('Images/Icons/Menu/Settings.png') }}" alt=""/><span>Settings</span></a>
        </div>

        <p><span id="icons8">Icons by -> </span> <a href="https://icons8.com/" target="_blank">Icons8</a></p>
    </nav>

    <div class="top-menu" id="top-menu">
        <div class="flex">
            <div style="display: flex; align-items: center; gap: 14px;">
                @yield('go-back')
                <h1 style="margin: 0px">@yield('body-title')</h1>
            </div>

            <div class="user" onclick="toggleProfile()">
                <img src="{{ asset($user->pfp) }}" alt="" class="pfp">
                <div style="display: block; align-items: center">
                    <p class="SQL" style="font-weight:700; font-size: 15px;">{{ $user->name }}</p>
                    <p class="SQL" style="font-size:13px;">{{ $user->email }}</p>
                </div>
                <span><img id="arrowP" class="icon" src="{{ asset('Images/Icons/Menu/ProfileToggle.png') }}"></span>
            </div>
        </div>
    </div>

    <div class="profile-toggle" id="profile-toggle" style="top: -90px;">
        <a href="/"><button style="border-radius: 6px 6px 0px 0px">Home</button></a>
        <div class="space"></div>
        <a href="/dashboard/my-profile"><button>My Profile</button></a>
        <div class="space"></div>
        <a href="/dashboard/settings"><button>Settings</button></a>
        <div class="space"></div>
        <form action="{{ route('logout') }}" method="POST" style="margin-bottom: 0px">
            @csrf
            <button type="submit" class="signOut" style="border-radius: 0px 0px 6px 6px">Sign Out</button>
        </form>
    </div>

    <div class="main_content" id="main_content">
        @yield('body')
    </div>
          
    @yield('custom_vue')
</body>
</html>