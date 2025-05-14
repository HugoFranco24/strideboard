@extends('layouts.sidemenu')
@section('title')
    Projects
@endsection

@section('body-title')
    Projects
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/projects.css') }}">
@endsection

@section('body')

    <div class="box">
        <h3>You have {{ $projects->count() }} {{ $projects->count() == 1 ? 'project' : 'projects'}}</h3>
        @foreach($projects as $p)
            <a href="/dashboard/projects/overview/{{ $p->id_project }}" title="Overview" class="overview">
                <div class="project">
                    <h4>{{ $p->name }}</h4>
                    <p>Business: {{ $p->business }}</p>
                    <p>Due Date: {{ $p->due_date }}</p>
                </div>
            </a>
        @endforeach

        <a href="{{ route('projects.create') }}"><button class="btn_default">Create Project</button></a>
    </div>
@endsection