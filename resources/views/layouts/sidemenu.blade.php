<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | StrideBoard</title>

    <link rel="stylesheet" href="@yield('css')">

    <link rel="stylesheet" href="{{ asset('css/dashboard/sidemenu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/general.css') }}">

    <link rel="icon" href="{{ asset('Images/Logos/Strideboard.png') }}" type="image/x-icon">
</head>
<body>
    <div class="hamburger" id="toogleM" onclick="toggleMenu()">
        <img src="https://img.icons8.com/ios-glyphs/90/ffffff/left--v3.png" alt="" width="35" height="35" id="close">
        <img src="https://img.icons8.com/ios-glyphs/90/ffffff/right--v3.png" alt="" width="35" height="35" id="open">
    </div>

    <nav id="nav"  style="width: 300px;"> {{-- {{ $Mopen === True? 'style="left: 0px;"': 'style="left: -200px;"' }} --}}
        <div class="links" id="links">
            <span class="sep">Manage</span>
            <a href="/dashboard"><img src="https://img.icons8.com/material-outlined/96/ffffff/control-panel.png" alt=""><span>Dashboard</span></a>
            <a href="/dashboard/projects"><img src="https://img.icons8.com/material/96/ffffff/clipboard--v1.png" alt=""><span>Projects</span></a>
            <a href="/dashboard/tasks"><img src="https://img.icons8.com/material/96/ffffff/task-completed.png" alt=""/><span>Tasks</span></a>
            <a href="/dashboard/calendar"><img src="https://img.icons8.com/material/96/ffffff/tear-off-calendar.png" alt=""><span>Calendar</span></a>
            <div class="sepC" id="sepC"></div>
            <span class="sep">Settings</span>
            <a href="/dashboard/messages"><img src="https://img.icons8.com/material/96/ffffff/new-post--v1.png" alt="" ><span>Messages</span></a>
            <a href="/dashboard/profile"><img src="https://img.icons8.com/material/100/ffffff/user--v1.png" alt=""/><span>Profile</span></a>
            <a href="/dashboard/settings"><img src="https://img.icons8.com/ios-filled/100/ffffff/settings.png" alt=""/><span>Settings</span></a>
        </div>


        <p ><span id="icons8">Icons by :</span><a href="https://icons8.com/" target="_blank">Icons8</a></p>
    </nav>

    <div class="top-menu" id="top-menu">
        <div class="flex">
            <div style="display: flex; align-items: center; gap: 20px;">
                <a class="goBack" href="javascript:history.back()"><img width="35" height="35" src="https://img.icons8.com/fluency-systems-filled/48/u-turn-to-left.png" alt="undo" title="Go Back"/></a>
                <h1 style="margin: 0px">@yield('body-title')</h1>
            </div>
            
            <div class="user" onclick="toggleProfile()">
                <img src="{{ asset($user->pfp ?? 'Images/Pfp/pfp_default.png') }}" alt="">
                <div style="display: block; align-items: center">
                    <p style="font-weight:700; font-size: 15px;">{{ $user->name }}</p>
                    <p style="font-size:13px ">{{ $user->email }}</p>
                </div>
                <span><img id="arrowP" src="https://img.icons8.com/metro/26/forward.png"></span>
            </div>
        </div>
    </div>

    <div class="profile-toggle" id="profile-toggle" style="top: -90px;">
        <a href="/dashboard/profile"><button style="border-radius: 10px 10px 0px 0px">My Profile</button></a>
        <div class="space"></div>
        <a href="/dashboard/settings"><button><img src="" alt="">Settings</button></a>
        <div class="space"></div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0px;">
            @csrf
            <button type="submit" class="signOut" style="border-radius: 0px 0px 10px 10px">Sign Out</button>
        </form>
    </div>

    <div class="main_content" id="main_content">
        @yield('body')
    </div>
</body>
</html>

<script>
    function toggleMenu(){

        var menu = document.getElementById("nav");
        var img = document.getElementById("toogleM");

        var close = document.getElementById("close");
        var open = document.getElementById("open");

        var icons8 = document.getElementById("icons8");

        var links = document.getElementById("links");

        var top_menu = document.getElementById("top-menu");
        var main = document.getElementById("main_content");

        var sepC = document.getElementById("sepC");

        if (menu.style.width === "300px") {
            menu.style.width = "68px";
            img.style.left = "6px";
            img.style.transition = "500ms";
            open.style.opacity = "1";
            close.style.opacity = "0";
            icons8.style.display = "none";
            links.classList.toggle('links_colapse');
            top_menu.style.margin = "0px 0px 0px 68px"
            main.style.padding = "50px 0px 0px 108px";
            sepC.style.visibility = "visible";
        }else{
            menu.style.width = "300px";
            img.style.left = "230px";
            open.style.opacity = "0";
            close.style.opacity = "1";
            icons8.style.display = "block";
            links.classList.toggle('links_colapse');
            top_menu.style.margin = "0px 0px 0px 300px"
            main.style.padding = "50px 0px 0px 340px";
            sepC.style.visibility = "hidden";

        }
    }

    function toggleProfile(){
        var profile_toggle = document.getElementById('profile-toggle');
        var arrowP = document.getElementById('arrowP');

        if(profile_toggle.style.top == '-90px'){
            profile_toggle.style.top = '90px';
            // arrowP.style.transform = 'rotate(90deg)';
            arrowP.classList.add('rotated');
        }else{
            profile_toggle.style.top = '-90px';
            // arrowP.style.transform = 'rotate(-0deg)';
            arrowP.classList.remove('rotated');
        }
    }

    function toogleTheme(){
        var 
    }
</script>