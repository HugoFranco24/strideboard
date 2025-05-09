@extends('layouts.sidemenu')
@section('title')
    {{ isset($project) ? 'Update' : 'Create' }} Project
@endsection

@section('body-title')
    {{ isset($project) ? 'Update' : 'Create' }} Project
@endsection

@section('css')
    {{ asset("css/dashboard/projects/projects-create.css") }}
@endsection

@section('body')
<div class="box">
    <h2>About Project</h2>
    <form action="{{ isset($project) ? route('projects.update', $project->id_project) : route('projects.add') }}" method="POST">
        @csrf

        <label>Project Name</label><br>
        <input type="text" name="name" style="margin-bottom: 10px" value="{{ old('name', $project->name ?? '') }}"><br>

        <label>Business</label><br>
        <input type="text" name="business" style="margin-bottom: 10px" value="{{ old('business', $project->business ?? '') }}"><br>

        <label>Due Date</label><br>
        <input type="date" name="due_date" style="margin-bottom: 10px" value="{{ old('due_date', $project->due_date ?? '') }}"><br>

        <button type="submit" class="btn_default">{{ isset($project) ? 'Update' : 'Create' }} Project</button>
    </form>
</div> 
@endsection
