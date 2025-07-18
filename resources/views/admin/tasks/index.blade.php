@extends('layouts.admin')

@section('title')
    Projects
@endsection

@section('main-content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Task Management</h1>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 72dvh">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Project Name</th>
                        <th scope="col" class="px-6 py-3">Assigned To</th>
                        <th scope="col" class="px-6 py-3">End Date</th>
                        <th scope="col" class="px-6 py-3">State</th>
                        <th scope="col" class="px-6 py-3">Priority</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $t)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $t->id }}</td>
                            <td class="px-6 py-4">{{ $t->name }}</td>
                            <td class="px-6 py-4"><a class="underline hover:no-underline" href="{{ route('admin.project.overview', $t->project_id) }}">{{ $t->project->name }}</a></td>
                            <td class="px-6 py-4">{{ $t->user->name }}</td>
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
                            <td class="px-6 py-6 flex space-x-2">
                                <a href="{{ route('admin.task.edit', $t->id) }}" class="cursor-pointer bg-gray-800 hover:bg-gray-950 text-white font-semibold py-1 px-3 rounded transition duration-200">Edit</a>
                                <form method="post" action="{{ route('admin.task.destroy', $t->id) }}" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                    @csrf
                                    @method('delete')

                                    <button class="cursor-pointer bg-red-700 hover:bg-red-800 text-white font-semibold py-1 px-3 rounded transition duration-200" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection