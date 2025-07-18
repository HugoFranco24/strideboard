@extends('layouts.admin')

@section('title')
    {{ isset($task) ? 'Edit' : 'Create' }} Task
@endsection

@section('main-content')
    <h1 class="mb-6 text-2xl font-bold text-gray-800">{{ isset($task) ? 'Edit' : 'Create' }} Task</h1>
    <form method="POST" action="{{ isset($task) ? route('admin.task.update', $task->id) : route('admin.task.store', $project->id) }}">
        @csrf
        @if (isset($task))
            @method('PUT')
        @endif
            
        <label for="user">Resposible for Task</label>
        <select name="user" id="user" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
            @foreach ($project->users as $u)
            <option value="{{ $u->id }}" {{ old('user') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user')" class="mt-2 mb-4"/>

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', isset($task) ? $task->name : '') }}" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
        <x-input-error :messages="$errors->get('name')" class="mt-2 mb-4"/>
                
        <label for="description">Description</label>
        <textarea rows="4" name="description" id="description" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">{{ old('description', isset($task) ? $task->description : '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2 mb-4"/>

        <label for="start">Start Date</label>
        <input id="start" type="date" name="start" value="{{ old('start', isset($task) ? $task->start : '') }}" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors"/>
        <x-input-error :messages="$errors->get('start')" class="mt-2 mb-4"/>

        <label for="end">End Date</label>
        <input id="end" type="date" name="end" value="{{ old('end', isset($task) ? $task->end : '') }}" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors"/>
        <x-input-error :messages="$errors->get('end')" class="mt-2 mb-4"/>

        <label>Status</label>
        <select name="state" id="state" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
            <option value="0" {{ old('state') == 0 || $task->state == 0 ? 'selected' : '' }}>To Do</option>
            <option value="1" {{ old('state') == 1 || $task->state == 1 ? 'selected' : '' }}>Stopped</option>
            <option value="2" {{ old('state') == 2 || $task->state == 2 ? 'selected' : '' }}>In Progress</option>
            <option value="3" {{ old('state') == 3 || $task->state == 3 ? 'selected' : '' }}>Done</option>
        </select>
        <x-input-error :messages="$errors->get('state')"/>

        <br>
        <label>Priority</label><br>
        <select name="priority" id="priority" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
            <option value="0" {{ old('priority') == 0 || $task->priority == 0 ? 'selected' : '' }}>Low</option>
            <option value="1" {{ old('priority') == 1 || $task->priority == 1 ? 'selected' : '' }}>Normal</option>
            <option value="2" {{ old('priority') == 2 || $task->priority == 2 ? 'selected' : '' }}>High</option>
            <option value="3" {{ old('priority') == 3 || $task->priority == 3 ? 'selected' : '' }}>Urgent</option>
        </select>
        <x-input-error :messages="$errors->get('priority')"/>

        <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm transition duration-200 cursor-pointer">
            {{ isset($task) ? 'Update' : 'Create' }} Task
        </button>
    </form>    
@endsection