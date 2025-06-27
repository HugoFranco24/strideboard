@extends('layouts.main')
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
    <link rel="stylesheet" href="{{ asset('css/dashboard/global/input-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/global/table.css') }}">
@endsection

@section('custom_links')
    <script src="https://unpkg.com/vue@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
@endsection

@section('body')
    <div id="app" v-cloak>
    <div class="container" style="grid-template-rows: repeat({{ isset($task) ? 11 : 4 }}, 1fr)">
        <div class="item">
            <h2>Project Details</h2>
            <p class="SQL"><span>Project Name:</span> {{ $project->name }}</p>
            <p class="SQL"><span>Business:</span> {{ $project->business == '' ? 'Not Defined' : $project->business }}</p>
            <p class="SQL"><span>Due Date:</span> {{ \Carbon\Carbon::parse($project->due_date)->format('F d, Y') }}</p>
            <div style="display: flex">
                <span>Project Color:   {{$project->color}} -></span>
                <div style="background-color: {{ $project->color }}; width: 40px; border-radius: 2px;"></div>
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
                    <form action="/dashboard/projects/overview/{{ $project->id }}/delete-member/{{ auth()->id() }}"
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
                    <input type="text" v-model="term" placeholder="Search User" style="margin: 0px; min-width: 100% !important;">
                    <div class="addmember-wrapper">
                        <table class="addmember">
                            <tr v-for="user in filteredUsers" :key="user.id">                        
                                <td><img :src="'/' + (user.pfp || 'Images/Pfp/pfp_default.png')" alt="" class="pfp"></td>
                                <td class="SQL" style="max-width: 200px"><a :href="'/dashboard/profile/' + user.id" class="username">@{{ user.name }}</a></td>
                                <td class="SQL" style="max-width: 200px">@{{ user.email }}</td>
                                <td>
                                    <form :action="'/dashboard/projects/overview/'+ project.id +'/add-member/'+ user.id" 
                                        class="addmember_form"
                                        method="post"
                                    >
                                        @csrf
                                        @method('post')
                                        <button type="submit">
                                            <img width="50" height="50" src="{{ asset('Images/Icons/Actions/UserAdd.png') }}" alt="plus-math--v1"/>
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
                            <td><img src="{{ asset($pu->pfp) }}" alt="" class="pfp"></td>
                            <td class="SQL" style="max-width: 175px"><a href="{{ route('profile.overview', $pu->id) }}" class="username">{{ $pu->name }}</a></td>
                            <td class="SQL" style="max-width: 175px">{{ $pu->email }}</td>
                            <td>
                                @if ($pu->pivot->active == 1)
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
                                @else
                                    Invite Pending
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
                                                <img style="min-width: 35px; max-width: 35px; height: 35px;" src="{{ asset('Images/Icons/Actions/UserDelete.png') }}" alt="UserDelete"/>
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
            @if ($total > 0)
                <p><span>My Tasks: {{ $my_tasks->count() }}</span></p>
                <p><span>Late: </span> {{ $late->count() }}</p>
                <p><span>Urgent: </span> {{ $urgent->count() }}</p>
                <p><span>Done: </span> {{ $done->count() }}</p>
            @else
                <br><br>
                <h2 style="font-weight: 600">No Tasks</h2>
                <br>
            @endif
            
            
            <div class="btns" style="margin-top: 40px">
                @if ($authUserType != 0)
                    <a href="/dashboard/projects/overview/{{$project->id}}/create-task"><button type="button" class="btn_default">Create Task</button></a>
                @endif
                
                @if ($total > 0)
                    <a href="#allTasks"><button class="btn_default">View All Tasks</button></a>
                @endif
            </div>  
        </div>
        @if ($total > 0)
            <div class="item">
                <div class="kanban-board">
                    <div class="column to-do">
                        <div class="title">
                            <h2>MY TASKS</h2>
                        </div>
                        @if ($my_tasks->count() > 0)
                            @foreach ($my_tasks as $t)
                                <div class="task-card">
                                    <div style="margin: 10px 10px 5px">
                                        <h3>{{$t->name}}</h3>
                                        <div class="space"></div>
                                        <p>
                                            Start: {{ \Carbon\Carbon::parse($t->start)->format('F d, Y') }} <br>
                                            End: {{ \Carbon\Carbon::parse($t->end)->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <div class="func">
                                        @if ($authUserType >= 1 || $t->user_id == auth()->id())
                                            <form 
                                                action="{{ route('task.delete', $t->id) }}"
                                                onsubmit="confirm('Are you sure you want to DELETE this task?')"
                                                method="post"
                                            >
                                                @csrf
                                                @method('delete')
                                                <button type="submit"><img  class="icon" src="{{ asset('Images/Icons/Actions/Delete.svg') }}"></button>
                                            </form>
                                        @endif
                                        <a href="{{ route('task.overview',  $t->id) }}"><img class="icon" src="{{ asset('Images/Icons/Actions/More.svg') }}"></a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="task-card">
                                <h3 style="margin: 10px; text-align: center;">No Owned Tasks</h3>
                            </div>
                        @endif
                    </div>
                    <div class="column late">
                        <div class="title">
                            <h2>LATE</h2>
                        </div>
                        @if ($late->count() > 0)
                            @foreach ($late as $l)
                                <div class="task-card">
                                    <div style="margin: 10px 10px 5px">
                                        <h3>{{$l->name}}</h3>
                                        <div class="space"></div>
                                        <p>
                                            Start: {{ \Carbon\Carbon::parse($l->start)->format('F d, Y') }} <br>
                                            End: {{ \Carbon\Carbon::parse($l->end)->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <div class="func">
                                        @if ($authUserType >= 1 || $l->user_id == auth()->id())
                                            <form 
                                                action="{{ route('task.delete', $l->id) }}"
                                                onsubmit="confirm('Are you sure you want to DELETE this task?')"
                                                method="post"
                                            >
                                                @csrf
                                                @method('delete')
                                                <button type="submit"><img  class="icon" src="{{ asset('Images/Icons/Actions/Delete.svg') }}"></button>
                                            </form>
                                        @endif
                                        <a href="{{ route('task.overview',  $l->id) }}"><img class="icon" src="{{ asset('Images/Icons/Actions/More.svg') }}"></a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="task-card">
                                <h3 style="margin: 10px; text-align: center;">No Late Tasks</h3>
                            </div>
                        @endif
                    </div>
                    <div class="column urgent">
                        <div class="title">
                            <h2>URGENT</h2>
                        </div>
                        @if($urgent->count() > 0)
                            @foreach($urgent as $u)
                                <div class="task-card">
                                    <div style="margin: 10px 10px 5px">
                                        <h3>{{$u->name}}</h3>
                                        <div class="space"></div>
                                        <p>
                                            Start: {{ \Carbon\Carbon::parse($u->start)->format('F d, Y') }} <br>
                                            End: {{ \Carbon\Carbon::parse($u->end)->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <div class="func">
                                        @if ($authUserType >= 1 || $u->user_id == auth()->id())
                                            <form 
                                                action="{{ route('task.delete', $u->id) }}"
                                                onsubmit="confirm('Are you sure you want to DELETE this task?')"
                                                method="post"
                                            >
                                                @csrf
                                                @method('delete')
                                                <button type="submit"><img  class="icon" src="{{ asset('Images/Icons/Actions/Delete.svg') }}"></button>
                                            </form>
                                        @endif
                                        <a href="{{ route('task.overview', $u->id) }}"><img class="icon" src="{{ asset('Images/Icons/Actions/More.svg') }}"></a>
                                        
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="task-card">
                                <h3 style="margin: 10px; text-align: center;">No Urgent Tasks</h3>
                            </div>
                        @endif
                    </div>
                    <div class="column done">
                        <div class="title">
                            <h2>DONE</h2>
                        </div>
                        @if ($done->count() > 0)
                            @foreach ($done as $d)
                                <div class="task-card">
                                    <div style="margin: 10px 10px 5px">
                                        <h3>{{$d->name}}</h3>
                                        <div class="space"></div>
                                        <p>
                                            Start: {{ \Carbon\Carbon::parse($d->start)->format('F d, Y') }} <br>
                                            End: {{ \Carbon\Carbon::parse($d->end)->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <div class="func">
                                        @if ($authUserType >= 1 || $d->user_id == auth()->id())
                                            <form 
                                                action="{{ route('task.delete', $d->id) }}"
                                                onsubmit="confirm('Are you sure you want to DELETE this task?')"
                                                method="post"
                                            >
                                                @csrf
                                                @method('delete')
                                                <button type="submit"><img  class="icon" src="{{ asset('Images/Icons/Actions/Delete.svg') }}"></button>
                                            </form>
                                        @endif
                                        <a href="{{ route('task.overview',  $d->id) }}"><img class="icon" src="{{ asset('Images/Icons/Actions/More.svg') }}"></a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="task-card">
                                <h3 style="margin: 10px; text-align: center;">No Tasks Completed</h3>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="chart-container">
                    @if (!isset($task))
                        <canvas id="taskChart"></canvas>
                    @endif
                </div>
            </div>
            <div class="item">
                <div class="clock">
                    <h4 id="day"></h4>
                    <h4 id="dayWeek"></h4>
                </div>
            </div>
            <div class="item">
                <h1 style="text-align: center; margin: 10px 0px 30px;">Tasks</h1>
                <div class="dtable">
                    <div class="dtable-wrapper">
                        <table id="allTasks">
                            <tr>
                                <th onclick="sortTask(0)">Name <span class="sort-arrow"></span></th>
                                <th onclick="sortTask(1)">Assigned To <span class="sort-arrow"></span></th>
                                <th onclick="sortTask(2)">Starting Date <span class="sort-arrow"></span></th>
                                <th onclick="sortTask(3)">Ending Date <span class="sort-arrow"></span></th>
                                <th onclick="sortTask(4)">Status <span class="sort-arrow"></span></th>
                                <th onclick="sortTask(5)">Priority <span class="sort-arrow"></span></th>
                                <th style="text-align: center">Actions</th>
                            </tr>
                            @foreach ($project->tasks as $t)
                                <tr>
                                    <td class="SQL">{{ $t->name }}</td>
                                    <td class="SQL"><a href="{{ route('profile.overview', $project->users->where('id', $t->user_id)->first()?->id) }}" class="username">{{ $project->users->where('id', $t->user_id)->first()?->name }}</a></td>
                                    <td class="SQL">{{ \Carbon\Carbon::parse($t->start)->format('F d, Y') }}</td>
                                    <td class="SQL">{{ \Carbon\Carbon::parse($t->end)->format('F d, Y') }}</td>
                                    @if ($t->state == 0)
                                        <td data-state="not_started" class="state"><span style="display: none">0</span>Not Started</td>
                                    @elseif ($t->state == 1)
                                        <td data-state="stoped" class="state"><span style="display: none">1</span>Stoped</td>
                                    @elseif ($t->state == 2)
                                        <td data-state="in_progress" class="state"><span style="display: none">2</span>In Progress</td>
                                    @elseif ($t->state == 3)
                                        <td data-state="done" class="state"><span style="display: none">3</span>Done</td>
                                    @endif

                                    @if ($t->priority == 0)
                                        <td data-priority="low" class="priority"><span style="display: none">0</span>Low</td>
                                    @elseif ($t->priority == 1)
                                        <td data-priority="normal" class="priority"><span style="display: none">1</span>Normal</td>
                                    @elseif ($t->priority == 2)
                                        <td data-priority="high" class="priority"><span style="display: none">2</span>High</td>
                                    @elseif ($t->priority == 3)
                                        <td data-priority="urgent" class="priority"><span style="display: none">3</span>Urgent</td>
                                    @endif

                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('task.overview',  $t->id) }}"><img class="icon" src="{{ asset('Images/Icons/Actions/More.svg') }}"></a>
                                            @if ($authUserType >= 1 || $t->user_id == auth()->id())
                                                <form 
                                                    action="{{ route('task.delete', $t->id) }}"
                                                    onsubmit="confirm('Are you sure you want to DELETE this task?')"
                                                    method="post"
                                                >
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit"><img  class="icon" src="{{ asset('Images/Icons/Actions/Delete.svg') }}"></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('custom_vue')
    <script src="{{ asset('js/project-overview.js') }}"></script>
    <script src="{{ asset('js/tableSort.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hash = window.location.hash;
            if (hash) {
                setTimeout(() => {
                    const el = document.querySelector(hash);
                    if (el) el.scrollIntoView({ behavior: 'auto' });
                }, 60);  // delay to ensure element is there
            }
        });
    </script>
@endsection