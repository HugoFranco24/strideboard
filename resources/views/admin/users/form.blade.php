@extends('layouts.admin')

@section('title')
    {{ isset($user) ? 'Edit' : 'Create' }} User
@endsection

@section('main-content')
    <h1 class="mb-6 text-2xl font-bold text-gray-800">{{ isset($user) ? 'Edit' : 'Create' }} User</h1>
    <form method="POST" enctype="multipart/form-data" action="{{ isset($user) ? route('admin.user.update', $user->id) : route('admin.user.store') }}">
        @csrf
        @if (isset($user))
            @method('PUT')
            <img src="{{ asset($user->pfp) }}" alt="Profile Picture" class="w-26 h-26 rounded-sm mb-6">
        @endif
        <label for="pfp">Profile Picture</label>
        <input type="file" id="pfp" name="pfp" accept="image/*" class="w-full mb-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        <x-input-error :messages="$errors->get('pfp')" class="mt-2 mb-4"/>

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
        <x-input-error :messages="$errors->get('name')" class="mt-2 mb-4"/>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
        <x-input-error :messages="$errors->get('email')" class="mt-2 mb-4"/>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" class="w-full mb-2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 hover:border-blue-600 transition-colors">
        <x-input-error :messages="$errors->get('password')" class="mt-2 mb-4"/>
        <div class="flex items-center gap-2 mb-4">
            <input type="checkbox" name="show_password" id="show_password" class="w-3 h-3" onclick="togglePassword()"><label for="show_password">Show Password</label>
        </div>  

        <label for="email_verified_at">Email Verified?</label><br>
        <div class="flex flex-col mb-4 mt-2">
            <label>
                <input type="radio" value="1" name="email_verified_at" {{ old('email_verified_at', isset($user) && $user->email_verified_at ? '1' : '') == '1' ? 'checked' : '' }}>
                Yes
            </label>
            <label>
                <input type="radio" value="0" name="email_verified_at" {{ old('email_verified_at', isset($user) && !$user->email_verified_at ? '0' : '') == '0' ? 'checked' : '' }}>
                No
            </label>
            <x-input-error :messages="$errors->get('email_verified_at')" class="mt-2 mb-4"/>
        </div>

        <label for="is_admin">Is Admin?</label><br>
        <div class="flex flex-col mb-4 mt-2">
            <label>
                <input type="radio" value="1" name="is_admin" {{ old('is_admin', isset($user) && $user->is_admin ? '1' : '') == '1' ? 'checked' : '' }}>
                Yes
            </label>
            <label>
                <input type="radio" value="0" name="is_admin" {{ old('is_admin', isset($user) && !$user->is_admin ? '0' : '') == '0' ? 'checked' : '' }}>
                No
            </label>
            <x-input-error :messages="$errors->get('is_admin')" class="mt-2 mb-4"/>
        </div>

        <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm transition duration-200 cursor-pointer">
            {{ isset($user) ? 'Update' : 'Create' }} User
        </button>
    </form>    
@endsection

@section('js')
    <script>
        function togglePassword(){
            var password = document.getElementById('password');
            password.type = password.type === "password" ? "text" : "password";
        }
    </script>
@endsection