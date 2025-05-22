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
                <img src="{{ asset('Images/Logos/StrideBoardSemContorno.png') }}" alt="">
                <div class="links">
                    <a href="#about">About the Product</a>
                    <a href="#waw">Who are We</a>
                    <a href="#hiw">How it Works</a>
                    <a href="#faq">FAQ</a>
                </div>
            </div>
            

            <div class="hamburger" onclick="toggleMenu()">
                <img src="https://img.icons8.com/ios-filled/150/menu--v1.png" alt="menu--v1"/>
            </div>
            <div id="menuMB" class="menuMB">
                <button class="closebtn" onclick="toggleMenu()">&times;</button>
                <a href="#about" onclick="toggleMenu()">About the Product</a>
                <a href="#waw" onclick="toggleMenu()">Who are We</a>
                <a href="#hiw" onclick="toggleMenu()">How it Works</a>
                <a href="#faq" onclick="toggleMenu()">FAQ</a>
             </div>

             @guest
                <div class="authN">
                    <a href="/login">Login</a>
                    <a href="/register">Get Started</a>
                </div>
            @endguest
            @auth
                <div class="authY">
                    <a href="/dashboard">Dashboard</a>
                </div>
            @endauth
        </nav>
        
        <div class="main">
            <div class="margin">
                <div class="heroSec1">
                    <div class="gif-panel">
                        <img src="{{ asset('Images/Home/HeroSec.gif') }}" alt="Project demo gif" />
                        <img src="{{ asset('Images/Logos/StrideBoardUPS.png') }}" alt="Strideboard" />
                    </div>
                    <div class="promo-panel">
                        <h2>“Strideboard keeps your goals in sight and your tasks on track.”</h2>
                        <span>Hugo Franco, creator of Strideboard</span>
                        <p>Track your progress without getting buried in features you don't need.</p>
                        <div class="links">
                        <a href="" class="primary">Get Started for Free!</a>
                        <a class="secondary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="heroSec2" id="about">
                    <div class="item">
                        <div class="box">
                            <div class="img">
                                <img src="{{ asset('Images/Home/Users.png') }}" alt="group"/>
                            </div>
                            <div class="content">
                                <h4>Collaborate Seamlessly</h4>
                                <p>Keep everyone on the same page with real-time updates and messaging!</p>
                            </div>
                        </div>
                        <div class="box">
                            <div class="img">
                                <img src="https://img.icons8.com/sf-regular/150/113F59/calendar-24.png" alt="calendar-24"/>
                            </div>
                            <div class="content">
                                <h4>Plan with Precision</h4>
                                <p>Create timelines, assign tasks, and set clear goals with ease!</p>
                            </div>
                        </div>
                        <div class="box">
                            <div class="img">
                                <img src="https://img.icons8.com/sf-black/128/113F59/bullish.png" alt="conference-call"/>
                            </div>
                            <div class="content">
                                <h4>Stay on Track</h4>
                                <p>Monitor tasks, deadlines, and team performance with visual dashboards!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about" id="about">
                    <h1>About the Product</h1>
                    <button onclick="toogleAbout()" id="ptBtn">See in Portuguese Pt/Pt</button>
                    <button onclick="toogleAbout()" id="engBtn" disabled>See in English Eng/UK</button>
                    <div class="grid" style="display: grid" id="eng">
                        <div>
                            <p>
                                The platform allows the creation of tasks, assignment to team members,
                                definition of deadlines and progress tracking in real time.<br>
                                With a clear interface and pratical functionalities, it's ideal as for individual use such as colaborative.
                            </p>
                        </div>
                        <div>
                            <p>
                                This project managing website was produced within the scope of my Professional Aptitude Test (PAP)
                                for the Professional Course of IT Technician - Systems.<br>
                                The objective is to create a funcional tool,
                                intuitive and adaptable to help users organize and follow their projects from the start to the end.
                            </p>
                        </div>
                        <div>
                            <p>
                                This project mirrors the knowledge acquired throughout the course
                                and demonstrates the capacity of solving common real world problems of organization and productivity.
                            </p>
                        </div>
                    </div>
                    <div class="grid" style="display: none" id="pt">
                        <div>
                            <p>
                                A plataforma permite a criação de tarefas, atribuição a membros da equipa,
                                definição de prazos e acompanhamento do progresso em tempo real.
                                Com uma interface clara e funcionalidades práticas, é ideal tanto para uso individual como colaborativo.
                            </p>
                        </div>
                        <div>
                            <p>
                                Este website de gestão de projetos foi desenvolvida no âmbito da minha Prova de Aptidão Profissional (PAP)
                                do Curso Profissional de Técnico de Informática - Sistemas.<br>
                                O objetivo é criar uma ferramenta funcional,
                                intuitiva e adaptável para ajudar utilizadores a organizarem e acompanharem os seus projetos do início ao fim.                              
                            </p>
                        </div>
                        <div>
                            <p>
                                Este projeto reflete os conhecimentos adquiridos ao longo do curso
                                e demonstra a capacidade de desenvolver soluções reais para problemas comuns de organização e produtividade.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="waw" id="waw">
                    <h1>Who Are We</h1>
                    <h3>(Well It's actually just me)</h3>
                    <div class="card">
                        <div class="img">
                            <img src="{{ asset('Images/Home/hugoPfp.jpg') }}">
                            <div class="name">
                                <h2>Hugo Franco</h2>
                                <h4>@hogu_franco</h4>
                            </div>
                        </div>
                        <div class="infos">
                            <p class="text">
                                Hello! I'm Hugo and I'm a student at Escola Secundária/3 Pinhal do Rei. I'm doing the last year in the Professional Course of IT Technician - Systems.
                            </p>
                            <p class="text">
                                Right now I'm looking for finishing the 12th year and enter an university to do Informatical Engineering.
                            </p>
                            <p class="text">
                                I'm currently using Laravel to make my web projects because it's easier and more effective to use and beacause it's what I learnt at my internship at Yudo Eu, S.a.
                            </p>
                            <div class="links">
                                <a href="https://www.instagram.com/hogu_franco/"><img src="https://img.icons8.com/fluency/96/instagram-new.png"></a>
                                <a href="https://www.instagram.com/hogu_franco/"><img src="https://img.icons8.com/color/96/linkedin.png"></a>
                                <div class="tooltipDiv">
                                    <button onclick="copyEmail()" onmouseout="outCopy()">
                                        <span class="tooltip" id="tooltip">Copy Email Address</span>
                                        <img src="https://img.icons8.com/color/128/gmail-new.png">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hiw" id="hiw">
                    <h1>How it Works</h1>
                </div>

                <div class="faq" id="faq">
                    <h1>Frequently Asked Questions</h1>
                    <h3>(FAQ)</h3>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            Is it Free?
                            <span class="arrow"><img src="{{ asset('Images/Home/ProfileToggle.png') }}" alt=""></span>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, it's a website made for educational purposes only so it can't be monetized.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            Can I access it through mobile?
                            <span class="arrow"><img src="{{ asset('Images/Home/ProfileToggle.png') }}" alt=""></span>
                        </button>
                        <div class="faq-answer">
                            <p>No, you can use with with a tablet or desktop, no mobile.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            Can I use it offline?
                            <span class="arrow"><img src="{{ asset('Images/Home/ProfileToggle.png') }}" alt=""></span>
                        </button>
                        <div class="faq-answer">
                            <p>No, you need ethernet to have real time progress.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            Does the system send notifications about updates or deadlines?
                            <span class="arrow"><img src="{{ asset('Images/Home/ProfileToggle.png') }}" alt=""></span>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, there is a tab for notifications sent by Strideboard.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            How can I organize myself with so many projects?
                            <span class="arrow"><img src="{{ asset('Images/Home/ProfileToggle.png') }}" alt=""></span>
                        </button>
                        <div class="faq-answer">
                            <p>We have a built in calendar to inform the user about all tasks and projects deadlines.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <h3>Strideboard | Projeto de Aptidão Profissional</h3>
            <p><span style="font-weight: 600">Realizado por:</span> Hugo Carreira Franco 3ºC Nº7 | Ano Letivo 2024/2025 | Escola Secundária Pinhal do Rei</p>
            <p>Icons by: <a href="https://icons8.com">Icons 8</a></p>
        </footer>
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

    //Languages
    //
    //
    function toogleAbout(){
        var ptDiv = document.getElementById("pt");
        var engDiv = document.getElementById("eng");

        var ptBtn = document.getElementById("ptBtn");
        var engBtn = document.getElementById("engBtn");

        if(ptDiv.style.display === "grid"){
            ptDiv.style.display = "none";
            engDiv.style.display = "grid";
        }else{
            ptDiv.style.display = "grid";
            engDiv.style.display = "none";
        }

        if(ptBtn.disabled === false){
            ptBtn.disabled = true;
            engBtn.disabled = false;
        }else{
            ptBtn.disabled = false;
            engBtn.disabled = true;
        }
    }
    //
    //
    //Languages

    //COPY Email
    //
    //
    function copyEmail() {

        var copyText = 'franco.carreira.hugo@gmail.com';

        navigator.clipboard.writeText(copyText);

        var tooltip = document.getElementById("tooltip");
        tooltip.innerHTML = "Copied!";
    }

    function outCopy() {
        var tooltip = document.getElementById("tooltip");
        tooltip.innerHTML = "Copy Email Address";
    }

    //
    //
    //COPY Email End

    //FAQ
    //
    //
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        // Add a click event listener to each question
        question.addEventListener('click', () => {
            // Close any other open answers except the one clicked
            faqQuestions.forEach(item => {
                if (item !== question) {
                    item.classList.remove('active'); // Remove 'active' class to reset arrow rotation
                    item.nextElementSibling.style.maxHeight = null; // Collapse the answer
                }
            });

            // Toggle 'active' class on the clicked question to rotate the arrow
            question.classList.toggle('active');

            // Select the corresponding answer div
            const answer = question.nextElementSibling;

            // Check if the answer is already open
            if (answer.style.maxHeight) {
                // If open, close it by resetting max-height
                answer.style.maxHeight = null;
            } else {
                // If closed, set max-height to scrollHeight to expand it
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        });
    });
    //
    //
    //FAQ END
</script>