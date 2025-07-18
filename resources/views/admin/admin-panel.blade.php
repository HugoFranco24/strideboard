@extends('layouts.admin')

@section('title')
    Admin Panel
@endsection

@section('main-content')
    <div class="flex flex-col gap-y-6">
        <a href="/admin-panel/users" class="flex items-center gap-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 px-5 rounded-md shadow-md transition-all duration-200 h-18">
            <img class="w-5 h-5" src="https://img.icons8.com/fluency-systems-filled/48/ffffff/conference-call.png" alt="conference-call"/>
            Check All Users
        </a>
        <a href="/admin-panel/projects" class="flex items-center gap-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 px-5 rounded-md shadow-md transition-all duration-200 h-18">
            <img class="w-5 h-5" src="https://img.icons8.com/fluency-systems-filled/48/ffffff/group-of-projects.png" alt="group-of-projects"/>
            Check All Projects
        </a>
        <a href="/admin-panel/tasks" class="flex items-center gap-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 px-5 rounded-md shadow-md transition-all duration-200 h-18">
            <img class="w-5 h-5" src="https://img.icons8.com/fluency-systems-filled/48/ffffff/todo-list.png" alt="todo-list"/>
            Check All Tasks
        </a>
    </div>
@endsection