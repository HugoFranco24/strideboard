<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin Panel | @yield('title') | {{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('Images/Logos/StrideBoard.png') }}">

        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        @yield('js')
    </head>
    <body>
        <!-- Top Navigation -->
        <nav class="bg-gray-900 text-white py-4 px-7 flex justify-between items-center fixed top-0 right-0 w-4/5" >
            <button onclick="history.back()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-sm cursor-pointer">Back</button>
            <div class="relative group">
                <button id="dropDownBtn" class="flex items-center space-x-2 text-lg hover:text-gray-300 focus:outline-none cursor-pointer">
                    <span>{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="dropDownMenu" class="absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg z-10 hidden">
                    <a href="/dashboard"class="block px-4 py-2 rounded-md hover:bg-gray-100">Dashboard</a>
                    <a href="/dashboard/my-profile" class="block px-4 py-2 rounded-md hover:bg-gray-100">Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left cursor-pointer block px-4 py-2 rounded-md hover:bg-gray-100 text-red-800">Sign Out</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="flex h-full">
            <!-- Sidebar -->
            <aside class="w-1/5 bg-gray-800 text-white p-6 fixed top-0 left-0 h-screen">
                <h1 class="text-2xl font-bold mb-10">Admin Panel</h1>
                <nav>
                    <ul>
                        <li class="mb-4"><a href="/dashboard" class="text-lg hover:text-gray-300">Dashboard</a></li>
                        <li class="mb-4"><a href="/admin-panel" class="text-lg hover:text-gray-300">Admin Panel</a></li>
                        <li class="mb-4"><a href="/admin-panel/users" class="text-lg hover:text-gray-300">Users</a></li>
                        <li class="mb-4"><a href="/admin-panel/projects" class="text-lg hover:text-gray-300">Projects</a></li>
                        <li class="mb-4"><a href="/admin-panel/tasks" class="text-lg hover:text-gray-300">Tasks</a></li>
                    </ul>
                </nav>
            </aside>

            <div class="w-1/5"></div>

            <!-- Main Content -->
            <main class="flex-1 p-8 overflow-auto mt-16">
                @yield('main-content')
            </main>
        </div>       
    </body>
</html>

<script>
    const btn = document.getElementById('dropDownBtn');
    const menu = document.getElementById('dropDownMenu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    // Optional: Hide menu if clicked outside
    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>