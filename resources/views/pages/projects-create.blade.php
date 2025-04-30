@extends('layouts.sidemenu')
@section('title')
    {{ isset($project) ? 'Update' : 'Create' }} Project
@endsection

@section('body-title')
    {{ isset($project) ? 'Update' : 'Create' }} Project
@endsection

@section('css')
    {{ asset("css/dashboard/projects-create.css") }}
@endsection

@section('body')
    <div class="box">
        <form action="{{ isset($project) ? route('dashboard.projects-update', $project->id_project) : route('dashboard.projects-add') }}" method="POST">
            @csrf
            
            <label>Project Name</label>
            <br>
            <input type="text" name="name" style="margin-bottom: 10px" value="{{ old('name', $project->name ?? '') }}">
            <br>

            <label>Business</label>
            <br>
            <input type="text" name="business" style="margin-bottom: 10px" value="{{ old('business', $project->business ?? '') }}">
            <br>

            <label>Due Date</label>
            <br>
            <input type="date" name="due_date" style="margin-bottom: 10px" value="{{ old('due_date', $project->due_date ?? '') }}">
            <br>

            <label>Add Members</label>
            <h4 style="margin: 0">Not available yet</h4>
            <br><br>

            <button type="submit" class="btn_default">{{ isset($project) ? 'Update' : 'Create' }} Project</button>
        </form>
    </div>
@endsection