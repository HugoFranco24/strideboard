@extends('layouts.sidemenu')
@section('title')
    Project Overview
@endsection

@section('go-back')
    <a class="goBack" href="{{ route('dashboard.projects') }}">
        <img class="icon" width="35" height="35" src="{{ asset('Images/Icons/Menu/Go-back.png') }}" alt="undo" title="Go Back"/>
    </a>
@endsection

@section('body-title')
    Project Overview
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/projects/input-select.css') }}">
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
@endsection

@section('body')
    <div id="app" v-cloak>
    <div class="container">
        <div class="item">
            <h2>Project Details</h2>
            <p class="SQL"><span>Project Name:</span> {{ $project->name }}</p>
            <p class="SQL"><span>Business:</span> {{ $project->business == '' ? 'Not Defined' : $project->business }}</p>
            <p class="SQL"><span>Due Date:</span> {{ $project->due_date }}</p>
            <div style="display: flex">
                <span>Project Color:   {{$project->color}} -></span>
                <div style="background-color: {{ $project->color }}; width: 40px;"></div>
            </div>
            <br>
            <div class="btns">
                @if ($authUserType != 0)
                    <a href="/dashboard/projects/edit/{{ $project->id }}"><button type="button" class="btn_default">Edit Project</button></a>
                @endif
                
                @if ($authUserType == 2)
                    <form action="/dashboard/projects/delete/{{ $project->id }}"
                        onsubmit="return confirm('Are you sure you want to DELETE the project?')"
                        method="post"
                    >
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn_delete" style="margin-top: 20px;">Delete Project</button>
                    </form>
                @endif

                @if ($authUserType != 2)
                    <form action="/dashboard/projects/overview/{{ $project->id }}/delete-member/{{ $user->id }}"
                        onsubmit="return confirm('Are you sure you want to LEAVE the project?')"
                        method="post"
                    >
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn_default" style="margin-top: 20px;">Leave Project</button>
                    </form>
                @endif
            </div>        
        </div>
        <div class="item">
            <input type="hidden" id="users" data-users='@json($user_all)'>
            <input type="hidden" id="project" data-project='@json($project)'>
            <h2>Collaborators</h2>
            @if ($authUserType != 0)
                <h3>Add Collaborator</h3>
                <div>
                    <input type="text" v-model="term" placeholder="Search User" style="margin: 0px; width: 100%;">
                    <div class="addmember-wrapper">
                        <table class="addmember">
                            <tr v-for="user in filteredUsers" :key="user.id">                        
                                <td><img :src="'/' + (user.pfp || 'Images/Pfp/pfp_default.png')" alt="" class="pfp"></td>
                                <td class="SQL" style="max-width: 200px">@{{ user.name }}</td>
                                <td class="SQL" style="max-width: 200px">@{{ user.email }}</td>
                                <td>
                                    <form :action="'/dashboard/projects/overview/'+ project.id +'/add-member/'+ user.id" 
                                        class="addmember_form"
                                        method="post"
                                    >
                                        @csrf
                                        @method('post')
                                        <button type="submit">
                                            <img width="50" height="50" src="{{ asset('Images/Icons/UserAdd.png') }}" alt="plus-math--v1"/>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
            
            <h3>Collaborators in Project:</h3>
            <div class="members-wrapper">
                <table class="members">
                    @foreach ($project->users as $pu)
                        <tr>
                            <td><img src="{{ asset($pu->pfp ?? 'Images/Pfp/pfp_default.png') }}" alt="" class="pfp"></td>
                            <td class="SQL" style="max-width: 175px">{{ $pu->name }}</td>
                            <td class="SQL" style="max-width: 175px">{{ $pu->email }}</td>
                            <td>
                                @if ($pu->id != auth()->id())
                                    @if ($authUserType == 1 && $pu->pivot->user_type == 1)
                                        <span>Admin</span>
                                    @elseif ($authUserType != 0 && $pu->pivot->user_type == 0 || $authUserType == 2 && $pu->pivot->user_type == 1 )
                                        <form action="/dashboard/projects/overview/{{ $project->id }}/update-member/{{ $pu->id }}"
                                            method="post"    
                                        >
                                            @csrf
                                            @method('put')
                                            <select name="user_type" onchange="this.form.submit()">
                                                <option value="0" {{ $pu->pivot->user_type == 0 ? 'selected' : '' }}>Collaborator</option>
                                                <option value="1" {{ $pu->pivot->user_type == 1 ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>
                                    @else
                                        @if ($pu->pivot->user_type == 2)
                                            <span>Owner</span>
                                        @else
                                            <span>{{ $pu->pivot->user_type == 0 ? 'Collaborator' : 'Admin' }}</span>
                                        @endif
                                    @endif
                                @else
                                    @if ($pu->pivot->user_type == 2)
                                        <span>Owner</span>
                                    @else
                                        <span>{{ $pu->pivot->user_type == 0 ? 'Collaborator' : 'Admin' }}</span>
                                    @endif
                                @endif
                            </td>

                            @if ($pu->id != auth()->id())
                                @if ($authUserType == 1 && $pu->pivot->user_type == 1)
                                    <td></td>
                                @elseif ($authUserType != 0 && $pu->pivot->user_type == 0 || $authUserType == 2 && $pu->pivot->user_type == 1 )
                                    <td style="width: 50px">
                                        <form action="/dashboard/projects/overview/{{ $project->id }}/delete-member/{{ $pu->id }}" 
                                            onsubmit="return confirm('Are you sure you want to delete this user?');"
                                            method="post"
                                        >
                                            @csrf
                                            @method('delete')
                                            <button type="submit">
                                                <img style="min-width: 35px; max-width: 35px; height: 35px;" src="{{ asset('Images/Icons/UserDelete.png') }}" alt="UserDelete"/>
                                            </button>
                                        </form>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @else
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @php
            $doneT = 0;
            $in_progress = 0;
            $stoped = 0;
            $to_do = 0;
            $total = 0;

            foreach($project->tasks as $t){
                if($t->state == 0){
                    $to_do += 1;
                }elseif($t->state == 1){
                    $stoped += 1;
                }elseif($t->state == 2){
                    $in_progress += 1;
                }else{
                    $doneT += 1;
                }
                $total += 1;
            }
        @endphp
        <div class="item">
            <input type="hidden" value="{{ $doneT }}" id="done">
            <input type="hidden" value="{{ $in_progress }}" id="in_progress">
            <input type="hidden" value="{{ $stoped }}" id="stoped">
            <input type="hidden" value="{{ $to_do }}" id="to_do">
            <h2>Tasks</h2>
            <p><span>Total:</span> {{ $total }}</p>
            <p><span>Late: </span> 2</p>
            <p><span>Urgent: </span> 2</p>
            <br><br>
            
            <div class="btns">
                @if ($authUserType != 0)
                    <a href="/dashboard/projects/overview/{{$project->id}}/create-task"><button type="button" class="btn_default">Create Task</button></a>
                @endif
                
                <a href=""><button class="btn_default">View All Tasks</button></a>
            </div>  
        </div>
        @if ($total > 0)
            <div class="item">
                <div class="kanban-board">
                    <div class="column to-do">
                        <div class="title">
                            <h2>MY TASKS</h2>
                        </div>
                        
                        @foreach ($my_tasks as $t)
                            <div class="task-card">
                                <div style="margin: 10px 10px 5px">
                                    <h3>{{$t->name}}</h3>
                                    <div class="space"></div>
                                    <p>
                                        Start: {{ $t->start }} <br>
                                        End: {{ $t->end }}
                                    </p>
                                </div>
                                <div class="func">
                                    <a href="{{ route('task.overview', $t->id) }}"><img class="icon" src="{{ asset('Images/Icons/Overview/More.svg') }}"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="column late">
                        <div class="title">
                            <h2>LATE</h2>
                        </div>
                        @foreach ($late as $l)
                            <div class="task-card">
                                <div style="margin: 10px 10px 5px">
                                    <h3>{{$l->name}}</h3>
                                    <div class="space"></div>
                                    <p>
                                        Start: {{ $l->start }} <br>
                                        End: {{ $l->end }}
                                    </p>
                                </div>
                                <div class="func">
                                    <a href="{{ route('task.overview', $l->id) }}"><img class="icon" src="{{ asset('Images/Icons/Overview/More.svg') }}"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="column urgent">
                        <div class="title">
                            <h2>URGENT</h2>
                        </div>
                        @foreach ($urgent as $u)
                            <div class="task-card">
                                <div style="margin: 10px 10px 5px">
                                    <h3>{{$u->name}}</h3>
                                    <div class="space"></div>
                                    <p>
                                        Start: {{ $u->start }} <br>
                                        End: {{ $u->end }}
                                    </p>
                                </div>
                                <div class="func">
                                    <a href="{{ route('task.overview', $u->id) }}"><img class="icon" src="{{ asset('Images/Icons/Overview/More.svg') }}"></a>
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="column done">
                        <div class="title">
                            <h2>DONE</h2>
                        </div>
                        @foreach ($done as $d)
                            <div class="task-card">
                                <div style="margin: 10px 10px 5px">
                                    <h3>{{$d->name}}</h3>
                                    <div class="space"></div>
                                    <p>
                                        Start: {{ $d->start }} <br>
                                        End: {{ $d->end }}
                                    </p>
                                </div>
                                <div class="func">
                                    <a href="{{ route('task.overview', parameters: $d->id) }}"><img class="icon" src="{{ asset('Images/Icons/Overview/More.svg') }}"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="chart-container">
                    <canvas id="taskChart"></canvas>
                </div>
            </div>
            <div class="item">
                <div class="clock">
                    <h4 id="day"></h4>
                    <h4 id="dayWeek"></h4>
                </div>
            </div>
            <div class="item">
                <table class="members">
                    <tr>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                    @foreach ($project->tasks as $t)
                        <tr>
                            <td class="SQL">{{ $t->name }}</td>
                            <td class="SQL">{{ $t->start }}</td>
                            <td class="SQL">{{ $t->end }}</td>
                            <td>
                                @if ($t->state == 0)
                                    To Do
                                @elseif ($t->state == 1)
                                    In Progress
                                @else
                                    Done
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@section('custom_vue')
    <script src="{{ asset('js/project-overview.js') }}"></script>
@endsection
