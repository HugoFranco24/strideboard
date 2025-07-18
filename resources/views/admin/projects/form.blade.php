@extends('layouts.admin')

@section('title')
    {{ isset($project) ? 'Edit' : 'Create' }} Project
@endsection

@section('main-content')
    <h1 class="mb-6 text-2xl font-bold text-gray-800">{{ isset($project) ? 'Edit' : 'Create' }} Project</h1>
    <form method="POST" action="{{ isset($project) ? route('admin.project.update', $project->id) : route('admin.project.store') }}">
        @csrf
        @if (isset($project))
            @method('PUT')
        @endif

        <label for="owner">Owner</label>
        <select name="owner" id="owner" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
            @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ old('owner') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('owner')" class="mt-2 mb-4"/>

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', isset($project) ? $project->name : '') }}" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
        <x-input-error :messages="$errors->get('name')" class="mt-2 mb-4"/>

        <label for="business">Business</label>
        <input id="business" type="text" name="business" value="{{ old('business', isset($project) ? $project->business : '') }}" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
        <x-input-error :messages="$errors->get('business')" class="mt-2 mb-4"/>

        <label for="due_date">Due Date</label>
        <input id="due_date" type="date" name="due_date" value="{{ old('due_date', isset($project) ? $project->due_date : '') }}" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors"/>
        <x-input-error :messages="$errors->get('due_date')" class="mt-2 mb-4"/>

        <label for="color">Color</label>
        <input type="color" name="color" id="color" value="{{ old('color', $project->color ?? '') }}" class="w-full h-11 mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
        <x-input-error :messages="$errors->get('state')" class="mt-2 mb-4"/>

        <label>Archived?</label><br>
        <div class="flex flex-col mb-4 mt-2">
            <label>
                <input type="radio" value="1" name="archived" {{ old('archived', isset($project) && $project->archived ? '1' : '') == '1' ? 'checked' : '' }}>
                Yes
            </label>
            <label>
                <input type="radio" value="0" name="archived" {{ old('archived', default: isset($project) && !$project->archived ? '0' : '') == '0' ? 'checked' : '' }}>
                No
            </label>
            <x-input-error :messages="$errors->get('archived')" class="mt-2 mb-4"/>
        </div>

        <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm transition duration-200 cursor-pointer">
            {{ isset($project) ? 'Update' : 'Create' }} Project
        </button>
    </form>    
@endsection