<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta
            name="description"
            content="Projeto de Aptidão Profissional | Hugo Carreira Franco 3ºC Nº7 | Ano Letivo 2024/2025 | Escola Secundária Pinhal do Rei"
        >
        <title>StrideBoard - Home</title>
        <link rel="icon" href="{{ asset('Images/Logos/StrideBoard.png') }}">

        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    </head>
    <body>
        <nav>
            <div class="menu">
                <img src="{{ asset('Images/Logos/StrideBoardContornoV2.png') }}" alt="">
                <div class="links">
                    <a href="">About the Product</a>
                    <a href="">Who are We</a>
                    <a href="">Tutorials</a>
                </div>
            </div>
            

            <div class="hamburger" onclick="toggleMenu()">
                <img src="{{ asset('Images/Home/HamBar.png') }}"/>
            </div>
            <div id="menuMB" class="menuMB">
                <a href="#" class="closebtn" onclick="toggleMenu()">&times;</a>
                <a href="">About the Product</a>
                <a href="">Who are We</a>
                <a href="">Tutorials</a>
             </div>

             @guest
                <div class="authN">
                    <a href="/register">Sign Up</a>
                    <a href="/login">Sign In</a>
                </div>
            @endguest
            @auth
                <div class="authY">
                    <a href="/dashboard">Dashboard</a>
                </div>
            @endauth
        </nav>
        
        <div class="HeroSec">
            <img src="{{ asset('Images/Home/heroSec.jpg') }}" alt="">
            <div class="text">
                <div>
                    <h3>WELCOME TO STRIDEBOARD</h3>
                    <p>Free Project Managing Website for your business or personal use</p>
                </div>
            </div>
        </div>


        <div style="margin-top: 1000px"></div>
    </body>
</html>

<script>
    function toggleMenu(){
        var menu = document.getElementById("menuMB");

        if (menu.style.left === "0px") {
            menu.style.left = "-300px";
        } else {
            menu.style.left = "0px";
        }
    }
</script>