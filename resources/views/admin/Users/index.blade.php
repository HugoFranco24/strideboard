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
        <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
        <a href="{{ route('admin.user.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm transition duration-200 cursor-pointer">
            Create New User
        </a>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 72dvh">
            <table class="w-full text-sm text-left text-gray-700 max-h-20">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col">Profile Picture</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Is Admin</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $u->id }}</td>
                            <td class="px-6 py-3">
                                <img src="{{ asset($u->pfp) }}" alt="User pfp" class="w-12 h-12 rounded-full object-cover">
                            </td>
                            <td class="px-6 py-4">{{ $u->name }}</td>
                            <td class="px-6 py-4">{{ $u->email }}</td>
                            <td class="px-6 py-4">{{ $u->is_admin ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-6 flex space-x-2">
                                <a href="{{ route('admin.user.edit', $u->id) }}" class="cursor-pointer bg-gray-800 hover:bg-gray-950 text-white font-semibold py-1 px-3 rounded transition duration-200">Edit</a>
                                <form method="post" action="{{ route('admin.user.destroy', $u->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?')">
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