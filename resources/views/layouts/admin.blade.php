<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin Panel | @yield('title') | {{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('Images/Logos/StrideBoard.png') }}">

        <link rel="stylesheet" href="">
        @yield('css')

        @yield('js')
    </head>
    <body>
        <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white p-6">
            <h1 class="text-2xl font-bold mb-8">Admin Panel</h1>
            <nav>
                <ul>
                    <li class="mb-4"><a href="#users" class="text-lg hover:text-gray-300">Usuários</a></li>
                    <li class="mb-4"><a href="#projects" class="text-lg hover:text-gray-300">Projetos</a></li>
                    <li class="mb-4"><a href="#tasks" class="text-lg hover:text-gray-300">Tarefas</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-auto">
            <!-- Users Section -->
            <section id="users" class="mb-12">
                <h2 class="text-2xl font-semibold mb-4">Gerenciar Usuários</h2>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-medium">Lista de Usuários</h3>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Adicionar Usuário</button>
                    </div>
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-2">ID</th>
                                <th class="border p-2">Nome</th>
                                <th class="border p-2">Email</th>
                                <th class="border p-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border p-2">1</td>
                                <td class="border p-2">João Silva</td>
                                <td class="border p-2">joao@example.com</td>
                                <td class="border p-2">
                                    <button class="text-blue-500 hover:underline">Editar</button>
                                    <button class="text-red-500 hover:underline ml-2">Excluir</button>
                                </td>
                            </tr>
                            <!-- More user rows can be added dynamically -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Projects Section -->
            <section id="projects" class="mb-12">
                <h2 class="text-2xl font-semibold mb-4">Gerenciar Projetos</h2>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-medium">Lista de Projetos</h3>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Adicionar Projeto</button>
                    </div>
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-2">ID</th>
                                <th class="border p-2">Nome do Projeto</th>
                                <th class="border p-2">Responsável</th>
                                <th class="border p-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border p-2">1</td>
                                <td class="border p-2">Projeto Website</td>
                                <td class="border p-2">João Silva</td>
                                <td class="border p-2">
                                    <button class="text-blue-500 hover:underline">Editar</button>
                                    <button class="text-red-500 hover:underline ml-2">Excluir</button>
                                </td>
                            </tr>
                            <!-- More project rows can be added dynamically -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Tasks Section -->
            <section id="tasks">
                <h2 class="text-2xl font-semibold mb-4">Gerenciar Tarefas</h2>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-medium">Lista de Tarefas</h3>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Adicionar Tarefa</button>
                    </div>
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-2">ID</th>
                                <th class="border p-2">Tarefa</th>
                                <th class="border p-2">Projeto</th>
                                <th class="border p-2">Status</th>
                                <th class="border p-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border p-2">1</td>
                                <td class="border p-2">Criar Homepage</td>
                                <td class="border p-2">Projeto Website</td>
                                <td class="border p-2">Em Andamento</td>
                                <td class="border p-2">
                                    <button class="text-blue-500 hover:underline">Editar</button>
                                    <button class="text-red-500 hover:underline ml-2">Excluir</button>
                                </td>
                            </tr>
                            <!-- More task rows can be added dynamically -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal for Adding/Editing (Example for Users) -->
    <div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-lg font-medium mb-4">Adicionar/Editar Usuário</h3>
            <form>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nome</label>
                    <input type="text" class="w-full border p-2 rounded" placeholder="Nome do usuário">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" class="w-full border p-2 rounded" placeholder="Email do usuário">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancelar</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    </body>
</html>