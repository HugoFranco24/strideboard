@extends('layouts.sidemenu')
@section('title')
    Projects
@endsection

@section('css')
    {{ asset("css/dashboard/projects.css") }}
@endsection

@section('body')
    <h1 class="main_title">Projects</h1>

    <div class="box">
        <h3>You have {{ $projects->count() }} {{ $projects->count() == 1 ? 'project' : 'projects'}}</h3>
        @foreach($projects as $p)
            <div class="project">
                <h4>{{ $p->name }}</h4>
                <p>Business: {{ $p->business }}</p>
                <p>Due Date: {{ $p->due_date }}</p>
            </div>
        @endforeach

        <a href="{{ route('dashboard.projects-create') }}"><button class="createP">Create Project</button></a>
    </div>
@endsection