@extends('layouts.main')
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
        <div class="projects-wrapper">
            @foreach($projects as $p)
                <a href="/dashboard/projects/overview/{{ $p->id }}" title="Overview" class="overview">
                    <div class="project" style="--project-bar-color: {{ $p->color }}">
                        <div style="margin-left: 25px;">
                            <h4>{{ $p->name }}</h4>
                            <p>Business: {{ $p->business == '' ? 'Not Defined' : $p->business }}</p>
                            @if ($p->due_date < \Carbon\Carbon::today())
                                <p style="display: flex; align-items: center;">Due Date: {{ \Carbon\Carbon::parse($p->due_date)->format('F d, Y') }} <img src="{{ asset('Images/Icons/Actions/Warning.png') }}" width="20" height="20" title="You Project Is Overdue!"/></p>
                            @else
                                <p>Due Date: {{ \Carbon\Carbon::parse($p->due_date)->format('F d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <a href="{{ route('projects.create') }}"><button class="btn_default">Create Project</button></a>
    </div>
@endsection