@extends('layouts.sidemenu')
@section('title')
    {{ isset($project) ? 'Update' : 'Create' }} Project
@endsection

@section('go-back')
    <a class="goBack" href="{{ route('dashboard.projects') }}">
        <img class="icon" width="35" height="35" src="https://img.icons8.com/fluency-systems-filled/48/u-turn-to-left.png" alt="undo" title="Go Back"/>
    </a>
@endsection

@section('body-title')
    {{ isset($project) ? 'Update' : 'Create' }} Project
@endsection

@section('css')
    
@endsection

@section('body')
<div class="box">
    <h2>About Project</h2>
    <form action="{{ isset($project) ? route('projects.update', $project->id_project) : route('projects.add') }}" method="POST">
        @csrf

        <label>Project Name</label><br>
        <input type="text" name="name" style="margin-bottom: 10px" value="{{ old('name', $project->name ?? '') }}"><br>
        <x-input-error :messages="$errors->get('name')"/>

        <label>Business</label><br>
        <input type="text" name="business" style="margin-bottom: 10px" value="{{ old('business', $project->business ?? '') }}"><br>
        <x-input-error :messages="$errors->get('business')"/>

        <label>Due Date</label><br>
        <input type="date" name="due_date" style="margin-bottom: 10px" value="{{ old('due_date', $project->due_date ?? '') }}"><br>
        <x-input-error :messages="$errors->get('due_date')"/>

        <button type="submit" class="btn_default">{{ isset($project) ? 'Update' : 'Create' }} Project</button>
    </form>
</div> 
@endsection
