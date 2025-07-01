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
        <div class="project-count-archive">
            @if ($page == 'completed')
                <h3 style="margin: 0">You have {{ $projects->count() }} completed {{ $projects->count() == 1 ? 'project' : 'projects'}}</h3>
                <a href="{{ route('dashboard.projects') }}"><button style="margin-top: 0; min-width: 60px; width: auto;"><img class="icon" src="{{ asset('Images/Icons/Actions/GoToProjects.png') }}" alt="">See Active</button></a>
            @else
                <h3 style="margin: 0">You have {{ $projects->count() }} {{ $projects->count() == 1 ? 'project' : 'projects'}}</h3>
                <a href="{{ route('projects.archived') }}"><button style="margin-top: 0; min-width: 60px; width: auto;"><img class="icon" src="{{ asset('Images/Icons/Actions/CompleteProject.png') }}" alt="">See Completed</button></a>
            @endif
        </div>
        <div class="projects-wrapper">
            @foreach($projects as $p)
                <div class="project_box">
                    @if ($page == 'projects')
                        <a href="/dashboard/projects/overview/{{ $p->id }}" title="Overview" class="overview">
                    @endif
                        <div class="project" style="--project-bar-color: {{ $p->color }}; {{ $page == 'completed' ? 'cursor: default;' : ''}}">
                            <div style="margin-left: 25px;">
                                <h4>{{ $p->name }}</h4>
                                <p>Business: {{ $p->business == '' ? 'Not Defined' : $p->business }}</p>
                                @if ($p->due_date < \Carbon\Carbon::today() && $page == '')
                                    <p style="display: flex; align-items: center; gap: 4px;">Due Date: {{ \Carbon\Carbon::parse($p->due_date)->format('F d, Y') }} <img src="{{ asset('Images/Icons/Actions/Warning.png') }}" width="20" height="20" title="You Project Is Overdue!"/></p>
                                @else
                                    <p>Due Date: {{ \Carbon\Carbon::parse($p->due_date)->format('F d, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    @if ($page == 'projects')
                        </a>
                    @endif
                    @if ($page == 'projects')
                        <div class="project_menu_container">
                            <button class="project_toggle" onclick="toggleProjectOptions({{ $p->id }})"><img id="moreImg{{ $p->id }}" src="{{ asset('Images/Icons/Actions/VerticalDots.png') }}" alt=""></button>
                            <div class="project_options" id="project_options{{ $p->id }}" style="display: none">
                                @if ($p->pivot->user_type == 2)
                                    <form method="POST" action="{{ route('project.archive-toggle', $p->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit">
                                            {{ $page == 'completed' ? 'Restore' : 'Complete'}} 
                                            <img class="icon" src="{{ $page == 'completed' ? asset('Images/Icons/Actions/RestoreProject.png') : asset('Images/Icons/Actions/CompleteProject.png') }}" alt="">
                                        </button>                                    
                                    </form>
                                    <form method="POST" action="{{ route('projects.delete', $p->id) }}" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete <img class="icon" src="{{ asset('Images/Icons/Actions/Delete.png') }}" alt=""></button>
                                    </form>
                                @endif
                                @if ($p->pivot->user_type != 2)
                                    <a href="{{ route('projects.delete-member', $p->id) }}"><button style="{{ $p->pivot->user_type == 0 ? 'border-bottom: none; border-radius: 4px;' : '' }}">Leave Project <img class="icon" src="{{ asset('Images/Icons/Actions/LeaveProject.png') }}" alt=""></button></a>
                                @endif
                                @if ($p->pivot->user_type != 0)
                                    <a href="{{ route('projects.edit', $p->id) }}"><button style="border-bottom: none; border-radius: 4px;">Edit <img class="icon" src="{{ asset('Images/Icons/Actions/Edit.png') }}" alt=""></button></a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="project_menu_container">
                            <button class="project_toggle" onclick="toggleProjectOptions({{ $p->id }})"><img id="moreImg{{ $p->id }}" src="{{ asset('Images/Icons/Actions/VerticalDots.png') }}" alt=""></button>
                            <div class="project_options" id="project_options{{ $p->id }}" style="display: none">
                                @if ($p->pivot->user_type == 2)
                                    <form method="POST" action="{{ route('project.archive-toggle', $p->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit">
                                            {{ $page == 'completed' ? 'Restore' : 'Complete'}} 
                                            <img class="icon" src="{{ $page == 'completed' ? asset('Images/Icons/Actions/RestoreProject.png') : asset('Images/Icons/Actions/CompleteProject.png') }}" alt="">
                                        </button>                                    
                                    </form>
                                    <form method="POST" action="{{ route('projects.delete', $p->id) }}" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="border-bottom: none; border-radius: 4px;">Delete <img class="icon" src="{{ asset('Images/Icons/Actions/Delete.png') }}" alt=""></button>
                                    </form>
                                @endif
                                @if ($p->pivot->user_type != 2)
                                    <a href="{{ route('projects.delete-member', [$p->id, auth()->id()]) }}"><button style="border-bottom: none; border-radius: 4px;">Leave <img class="icon" src="{{ asset('Images/Icons/Actions/LeaveProject.png') }}" alt=""></button></a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <a href="{{ route('projects.create') }}"><button class="btn_default">Create Project</button></a>
    </div>
@endsection

@section('custom_vue')
    <script>
        function toggleProjectOptions(id){
            var options = document.getElementById('project_options' + id);
            var moreImg = document.getElementById('moreImg' + id);

            if(options.style.display == "none"){
                options.style.display = "block";
                moreImg.style.scale = "1.1";
            }else{
                options.style.display = "none";
                moreImg.style.scale = "";
            }
        }
    </script>
@endsection