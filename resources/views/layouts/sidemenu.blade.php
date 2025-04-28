<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | StrideBoard</title>

    <link rel="stylesheet" href="@yield('css')">

    <link rel="stylesheet" href="{{ asset('css/dashboard/sidemenu.css') }}">
    <link rel="icon" href="{{ asset('Images/Logos/Strideboard.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
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


        <p ><span id="icons8">Icons by :</span><a href="https://icons8.com/">Icons8</a></p>
    </nav>

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
            main.style.padding = "10px 0px 0px 108px";
            sepC.style.visibility = "visible";
        }else{
            menu.style.width = "300px";
            img.style.left = "230px";
            open.style.opacity = "0";
            close.style.opacity = "1";
            icons8.style.display = "block";
            links.classList.toggle('links_colapse');
            main.style.padding = "10px 0px 0px 340px";
            sepC.style.visibility = "hidden";

        }
    }
</script>