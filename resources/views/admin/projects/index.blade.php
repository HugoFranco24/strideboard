@extends('layouts.admin')

@section('title')
    Projects
@endsection

@section('main-content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Project Management</h1>
        <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 cursor-pointer">
            Create New Project
        </button>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
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
                            <td class="px-6 py-4">{{ $p->business }}</td>
                            <td class="px-6 py-4">{{ $p->due_date }}</td>
                            <td class="px-6 py-4">{{ $p->color }}</td>
                            <td class="px-6 py-4">{{ $p->archived ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-6 flex space-x-3">
                                <button class="cursor-pointer bg-blue-700 hover:bg-blue-800 text-white font-semibold py-1 px-3 rounded transition duration-200">View</button>
                                <button class="cursor-pointer bg-gray-800 hover:bg-gray-950 text-white font-semibold py-1 px-3 rounded transition duration-200">Edit</button>
                                <button class="cursor-pointer bg-red-700 hover:bg-red-800 text-white font-semibold py-1 px-3 rounded transition duration-200">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection