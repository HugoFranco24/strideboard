@extends('layouts.sidemenu')
@section('title')
    Create Project
@endsection

@section('css')
    {{ asset("css/dashboard/projects-create.css") }}
@endsection

@section('body')
    <h1 class="main_title">Create Project</h1>

    <div class="box">
        <form action="{{ route('dashboard.projects-create-add') }}" method="POST">
            @csrf
            
            <label>Project Name</label>
            <br>
            <input type="text" name="name" style="margin-bottom: 10px">
            <br>

            <label>Business</label>
            <br>
            <input type="text" name="business" style="margin-bottom: 10px">
            <br>

            <label>Due Date</label>
            <br>
            <input type="date" name="due_date" style="margin-bottom: 10px">
            <br>

            <label>Add Members</label>
            <h4 style="margin: 0">Not available yet</h4>
            <br><br>

            <button type="submit" class="btn_default">Create Project</button>
        </form>
    </div>
@endsection