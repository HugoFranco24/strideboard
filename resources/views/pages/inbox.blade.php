@extends('layouts.main')

@section('title')
    Inbox
@endsection

@section('body-title')
    Inbox
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/inbox.css') }}">
@endsection


@section('body')
    <div class="container">
        <div class="item">
            @if ($inbox->count() == 0)
                <p>No inbox items to read.</p>
            @else
                <div class="filters">
                    <button onclick="openFilter()"><img class="icon" src="{{ asset('Images/Icons/Actions/Filter.png')  }}">Filters</button>
                </div>
                <div class="notiBox">
                    @foreach ($inbox as $i)
                        <form action="{{ route('inbox.mark-read', $i->id) }}" method="post">
                            @csrf
                            <div class="noti" onclick="this.closest('form').submit()">
                                @switch($i->type)
                                    @case('invited')
                                        <h3>You got invited to a project.</h3>
                                        @break
                                    @case('removed')
                                        <h3>You got removed from a project.</h3>
                                        @break
                                    @case('changed_role')
                                        <h3>Your role was been changed in project.</h3>
                                        @break
                                    @case('left')
                                        <h3>A user left a project you're in.</h3>
                                        @break
                                    @case('accepted')
                                        <h3>Your project invite was accepted.</h3>
                                        @break
                                    @case('rejected')
                                        <h3>Your project invite was rejected.</h3>
                                        @break
                                    @case('created_task')
                                        <h3>A user created a task in a project you're in.</h3>
                                        @break
                                    @case('updated_task')
                                        <h3>A user updated a task in a project you're in.</h3>
                                        @break
                                    @case('deleted_task')
                                        <h3>A user deleted a task in a project you're in.</h3>
                                        @break
                                    @case('updated_project')
                                        <h3>A user updated a project you're in.</h3>
                                        @break
                                    @case('deleted_project')
                                        <h3>A user deleted a project you were in.</h3>
                                        @break
                                @endswitch
                                <p>{{ \Carbon\Carbon::parse($i->created_at)->translatedFormat('l, F jS Y \a\t H:i') }}</p>
                                @if ($i->is_read == false)
                                    <span class="point"></span>
                                @endif
                            </div>
                        </form>
                    @endforeach
                </div>
                <div class="markRead">
                    <form action="{{ route('inbox.mark-all-read') }}" method="POST">
                        @csrf
                        <button title="Mark All As Read"><img class="icon" src="{{ asset('Images/Icons/Actions/MarkAllRead.png') }}" alt="">Mark All as Read</button></a>
                    </form>
                </div>
            @endif
        </div>
        <div class="item">
            @if ($opened_noti == null)
                <p>Click on a message on the left to open it.</p>
            @else
                @php
                    $opened_noti_user = [2];
                    if($opened_noti->user == null){
                        $opened_noti_user[0] = "Images/Pfp/pfp_default.png";
                        $opened_noti_user[1] = "(Deleted User)";
                    }else{
                        $opened_noti_user[0] = $opened_noti->user->pfp;
                        $opened_noti_user[1] = $opened_noti->user->name;
                    }
                @endphp
                <div class="actions">
                    <div style="display: flex; gap: 6px;">
                        @if ($opened_noti->is_read == false)
                            <form action="{{ route('inbox.mark-read', $opened_noti->id) }}" method="post">
                                @csrf
                                <button title="Mark as Read"><img class="icon" src="{{ asset('Images/Icons/Actions/MarkRead.png') }}" alt=""></button>
                            </form>
                        @else
                            <form action="{{ route('inbox.mark-unread', $opened_noti->id) }}" method="post">
                                @csrf
                                <button title="Mark as Unread"><img class="icon" src="{{ asset('Images/Icons/Actions/MarkUnread.png') }}" alt=""></button>
                            </form>
                        @endif
                        <form action="{{ route('inbox.delete', $opened_noti->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button title="Delete Message"><img class="icon" src="{{ asset('Images/Icons/Actions/MessageDelete.png') }}" alt=""></button>
                        </form>
                    </div>
                    <div>
                        <a href="/dashboard/inbox"><button title="Close Message"><img class="icon" src="{{ asset('Images/Icons/Actions/Close.png') }}" alt=""></button></a>
                    </div>
                </div>

                <div class="notiDetails">
                    <div class="title">
                        <div style="align-items: center">
                            <img src="{{asset( $opened_noti_user[0]) }}" alt="">
                        </div>
                        <div>
                            <h3>{{ $opened_noti_user[1] }}</h3>
                            @switch($opened_noti->type)
                                @case('invited')
                                    <p>As invited you to a project.</p>
                                    @break
                                @case('removed')
                                    <p>As removed you from a project.</p>
                                    @break
                                @case('changed_role')
                                    <p>Your role was changed in project.</p>
                                    @break
                                @case('left')
                                    <p>As left a project you're in.</p>
                                    @break
                                @case('accepted')
                                    <p>As accepted your project invite.</p>
                                    @break
                                @case('rejected')
                                    <p>As rejected your project invite.</p>
                                    @break
                                @case('created_task')
                                    <p>As created a task in a project you're in.</p>
                                    @break
                                @case('updated_task')
                                    <p>As updated a task in a project you're in.</p>
                                    @break
                                @case('deleted_task')
                                    <p>As deleted a task in a project you're in.</p>
                                    @break
                                @case('updated_project')
                                    <p>As updated a project you're in.</p>
                                    @break
                                @case('deleted_project')
                                    <p>As deleted a project you were in.</p>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <div class="content">
                        @switch($opened_noti->type)
                            @case('invited')
                                <h3>{{ $opened_noti_user[1] }} as invited you to the Project "{{ $opened_noti->project_name }}"</h3>                          
                            
                                <div class="options">
                                    <form action="{{ route('projects.accept-invite', $opened_noti->reference_id) }}" method="post">
                                        @csrf
                                        <button class="btn_default">Accept Invite</button>
                                    </form>
                                    <form action="{{ route('projects.reject-invite', $opened_noti->reference_id) }}" method="post">
                                        @csrf
                                        <button class="btn_delete">Reject Invite</button>
                                    </form>
                                </div>
                            @break
                            @case('removed')
                                <h3>You have been removed form the project "{{ $opened_noti->project_name }}" by {{ $opened_noti->user->name }}.</h3>
                            @break
                            @case('changed_role')
                                <h3>Your role was changed in the "{{ $opened_noti->project_name }}" project by {{ $opened_noti->user->name }}.</h3>
                                <div class="options">
                                    <a href="{{ route('projects.overview', $opened_noti->reference_id) }}"><button class="btn_default">Check It Out</button></a>
                                </div>
                            @break
                            @case('left')
                                <h3>The user "{{ $opened_noti->user->name }}" as left the project "{{ $opened_noti->project_name }}".</h3>
                            @break
                            @case('accepted')
                                <h3>The user "{{ $opened_noti->user->name }}" as accepted your invite to join the project "{{ $opened_noti->project_name }}".</h3>
                                <div class="options">
                                    <a href="{{ route('projects.overview', $opened_noti->reference_id) }}"><button class="btn_default">Check It Out</button></a>
                                </div>
                            @break
                            @case('rejected')
                                <h3>The user "{{ $opened_noti->user->name }}" as rejected your invite to join the project "{{ $opened_noti->project_name }}".</h3>
                            @break
                            @case('created_task')
                                <h3>The user "{{ $opened_noti->user->name }}" as created the task "{{ $opened_noti->task_name }}" in the project "{{ $opened_noti->project_name }}".</h3>
                                <div class="options">
                                    <a href="{{ route('task.overview', $opened_noti->reference_id) }}"><button class="btn_default">Check It Out</button></a>
                                </div>
                            @break
                            @case('updated_task')
                                <h3>The user "{{ $opened_noti->user->name }}" as updated the task "{{ $opened_noti->task_name }}" in the project "{{ $opened_noti->project_name }}".</h3>
                                <div class="options">
                                    <a href="{{ route('task.overview', $opened_noti->reference_id) }}"><button class="btn_default">Check It Out</button></a>
                                </div>
                            @break
                            @case('deleted_task')
                                <h3>The user "{{ $opened_noti->user->name }}" as deleted the task "{{ $opened_noti->task_name }}" in the project "{{ $opened_noti->project_name }}".</h3>
                            @break
                            @case('updated_project')
                                <h3>The project "{{ $opened_noti->project_name }}" by the user {{ $opened_noti_user[1] }}.</h3>
                                <div class="options">
                                    <a href="{{ route('projects.overview', $opened_noti->reference_id) }}"><button class="btn_default">Check It Out</button></a>
                                </div>
                            @break
                            @case('deleted_project')
                                <h3>The project "{{ $opened_noti->project_name }}" by the user {{ $opened_noti_user[1] }}.</h3>
                            @break
                        @endswitch
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection