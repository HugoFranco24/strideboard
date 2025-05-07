@extends('layouts.sidemenu')
@section('title')
    Projects
@endsection

@section('body-title')
    Projects
@endsection

@section('css')
    {{ asset("css/dashboard/projects.css") }}
@endsection

@section('body')

    <div class="box">
        <h3>You have {{ $projects->count() }} {{ $projects->count() == 1 ? 'project' : 'projects'}}</h3>
        @foreach($projects as $p)
            <a href="/dashboard/projects/edit/{{ $p->id_project }}" title="Edit Project" class="edit">
                <div class="project">
                    <h4>{{ $p->name }}</h4>
                    <p>Business: {{ $p->business }}</p>
                    <p>Due Date: {{ $p->due_date }}</p>
                </div>
            </a>
        @endforeach

        <a href="{{ route('dashboard.projects-create') }}"><button class="btn_default">Create Project</button></a>
    </div>
@endsection