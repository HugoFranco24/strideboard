@extends('layouts.admin')

@section('title')
    Projects
@endsection

@section('main-content')
    @if (session('status'))
        <div class="mb-4 flex items-center justify-between rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </span>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                &times;
            </button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Project Management</h1>
        <a href="{{ route('admin.project.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm transition duration-200 cursor-pointer">
            Create New Project
        </a>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 72dvh">
            <table class="w-full text-sm text-left text-gray-700 max-h-20">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Business</th>
                        <th scope="col" class="px-6 py-3">Due Date</th>
                        <th scope="col" class="px-6 py-3">Color</th>
                        <th scope="col" class="px-6 py-3">Archived</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $p)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $p->id }}</td>
                            <td class="px-6 py-4">{{ $p->name }}</td>
                            <td class="px-6 py-4">{{ $p->business == '' ? 'Not Defined' : $p->business}}</td>
                            <td class="px-6 py-4">{{ $p->due_date }}</td>
                            <td class="px-6 py-4">{{ $p->color }}</td>
                            <td class="px-6 py-4">{{ $p->archived ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-6 flex space-x-3">
                                <a href="{{ route('admin.project.overview', $p->id) }}" class="cursor-pointer bg-blue-700 hover:bg-blue-800 text-white font-semibold py-1 px-3 rounded transition duration-200">View</a>
                                <a href="{{ route('admin.project.edit', $p->id) }}" class="cursor-pointer bg-gray-800 hover:bg-gray-950 text-white font-semibold py-1 px-3 rounded transition duration-200">Edit</a>
                                <form method="post" action="{{ route('admin.project.destroy', $p->id) }}" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                    @csrf
                                    @method('delete')

                                    <button class="cursor-pointer bg-red-700 hover:bg-red-800 text-white font-semibold py-1 px-3 rounded transition duration-200">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection