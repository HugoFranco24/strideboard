@extends('layouts.admin')

@section('title')
    Add Collaborator
@endsection

@section('main-content')
    <h1 class="mb-6 text-2xl font-bold text-gray-800">Add Collaborator</h1>
    <form method="POST" action="{{ route('admin.member.store', $project_id) }}">
        @csrf

        <label for="user">Collaborator to Add</label>
        <select name="user" id="user" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
            @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ old('user') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user')" class="mt-2 mb-4"/>

        <label for="user_type">User Type</label>
        <select name="user_type" id="user_type" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
            <option value="1" {{ old('user_type') == 1 ? 'selected' : '' }}>Admin</option>
            <option value="0" {{ old('user_type') == 0 ? 'selected' : '' }}>Collaborator</option>
        </select>
        <x-input-error :messages="$errors->get('user_type')" class="mt-2 mb-4"/>

        <label>Active?</label><br>
        <div class="flex flex-col mb-4 mt-2">
            <label>
                <input type="radio" value="1" name="active" {{ old('active') == '1' ? 'checked' : '' }}>
                Yes
            </label>
            <label>
                <input type="radio" value="0" name="active" {{ old('active') == '0' ? 'checked' : '' }}>
                No
            </label>
            <x-input-error :messages="$errors->get('active')" class="mt-2 mb-4"/>
        </div>

        <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm transition duration-200 cursor-pointer">
            Add Collaborator
        </button>
    </form>    
@endsection