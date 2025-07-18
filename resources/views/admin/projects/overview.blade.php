@extends('layouts.admin')

@section('title')
    Project Overview
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

    <h2 class="text-xl font-bold text-gray-800 mb-4">About the Project</h2>
    <div class="text-gray-800 w-full bg-gray-100 p-4 rounded-sm">
        <p class="my-2"><span class="font-bold">ID:</span> {{ $project->id }}</p>
        <p class="my-2"><span class="font-bold">Name:</span> {{ $project->name }}</p>
        <p class="my-2"><span class="font-bold">Business:</span> {{ $project->business == '' ? 'Not Defined' : $project->business }}</p>
        <p class="my-2"><span class="font-bold">Color:</span> {{ $project->color }}</p>
        <p class="my-2"><span class="font-bold">Archived:</span> {{ $project->archived ? 'Yes' : 'No    ' }}</p>

        <div class="flex gap-2 mt-6 w-1/3">
            <a href="{{ route('admin.project.edit', $project->id) }}" class="w-1/2 text-center cursor-pointer bg-gray-800 hover:bg-gray-950 text-white font-semibold py-2 px-3 rounded transition duration-200">Edit Project</a>
            <button class="w-1/2 cursor-pointer bg-red-700 hover:bg-red-800 text-white font-semibold py-2 px-3 rounded transition duration-200">Delete Project</button>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6 mt-14">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Collaborators</h2>
        <a href="{{ route('admin.member.create', $project->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm transition duration-200 cursor-pointer">
            Add New Collaborator
        </a>
    </div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 72dvh">
            <table class="w-full text-sm text-left text-gray-700 max-h-20">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Active</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->users as $u)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $u->id }}</td>
                            <td class="px-6 py-4">{{ $u->name }}</td>
                            <td class="px-6 py-4">{{ $u->email }}</td>
                            <td class="px-6 py-4">
                                @if ($u->pivot->user_type == 2)
                                    Owner
                                @else
                                    <form method="post" action="{{ route('admin.member.update-type', [$project->id, $u->id]) }}">
                                        @csrf
                                        @method('put')

                                        <select name="user_type" id="user_type" class="py-1 px-2 border-1 border-gray-300 rounded-sm focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors" onchange="this.form.submit()">
                                            <option value="1" {{ $u->pivot->user_type == 1 ? 'selected' : '' }}>Admin</option>
                                            <option value="0" {{ $u->pivot->user_type == 0 ? 'selected' : '' }}>Collaborator</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($u->pivot->user_type == 2)
                                    Yes
                                @else
                                    <form method="post" action="{{ route('admin.member.update-active', [$project->id, $u->id]) }}">
                                        @csrf
                                        @method('put')

                                        <select name="active" id="active" class="py-1 px-2 border-1 border-gray-300 rounded-sm focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors" onchange="this.form.submit()">
                                            <option value="1" {{ $u->pivot->active == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ $u->pivot->active == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-6 flex space-x-3">
                                <form method="post" action="{{ route('admin.member.destroy', [$u->pivot->project_id, $u->pivot->user_id ]) }}" onsubmit="return confirm('Are you sure you want to remove this user?')">
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

    <div class="flex justify-between items-center mb-6 mt-14">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Tasks</h2>
        <a href="{{ route('admin.task.create', $project->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm transition duration-200 cursor-pointer">
            Create New Task
        </a>
    </div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 72dvh">
            <table class="w-full text-sm text-left text-gray-700 max-h-20">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Assigned To</th>
                        <th scope="col" class="px-6 py-3">Start Date</th>
                        <th scope="col" class="px-6 py-3">End Date</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Priority</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->tasks as $t)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $t->id }}</td>
                            <td class="px-6 py-4">{{ $t->name }}</td>
                            <td class="px-6 py-4">{{ $project->users->where('id', $t->user_id)->first()?->name }}</td>
                            <td class="px-6 py-4">{{ $t->start }}</td>
                            <td class="px-6 py-4">{{ $t->end }}</td>
                            @if ($t->state == 0)
                                <td class="px-6 py-4">Not Started</td>
                            @elseif ($t->state == 1)
                                <td class="px-6 py-4">Stopped</td>
                            @elseif ($t->state == 2)
                                <td class="px-6 py-4">In Progress</td>
                            @elseif ($t->state == 3)
                                <td class="px-6 py-4">Done</td>
                            @endif

                            @if ($t->priority == 0)
                                <td class="px-6 py-4">Low</td>
                            @elseif ($t->priority == 1)
                                <td class="px-6 py-4">Normal</td>
                            @elseif ($t->priority == 2)
                                <td class="px-6 py-4">High</td>
                            @elseif ($t->priority == 3)
                                <td class="px-6 py-4">Urgent</td>
                            @endif
                            
                            <td class="px-6 py-6 flex space-x-3">
                                <a href="{{ route('admin.task.edit', $t->id) }}" class="cursor-pointer bg-gray-800 hover:bg-gray-950 text-white font-semibold py-1 px-3 rounded transition duration-200">Edit</a>
                                <form method="post" action="{{ route('admin.task.destroy', $t->id) }}" onsubmit="return confirm('Are you sure you want to delete this task?')">
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