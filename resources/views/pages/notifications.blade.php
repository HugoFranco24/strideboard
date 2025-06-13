@extends('layouts.main')

@section('title')
    Notifications
@endsection

@section('body-title')
    Notifications
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/notifications.css') }}">
@endsection


@section('body')

    <div class="container">
        <div class="item">
            <div class="unread">
                @php
                    $unreadNoti = 0;
                    foreach($notifications as $n){
                        if($n->is_read == false){
                            $unreadNoti += 1;
                        }
                    }
                @endphp
                <h4>You have {{ $notifications->count() }} {{ $notifications->count() == 1 ? 'notification' : 'notifications'}}{{ $unreadNoti >= 1 ? '(' . $unreadNoti . ' unread).' : '.' }}</h4>
                @if ($notifications->count() > 0)
                    <form action="{{ route('notification.mark-all-read') }}" method="POST" style="height: 36px">
                        @csrf
                        <button title="Mark All As Read"><img class="icon" src="{{ asset('Images/Icons/Actions/Seen.png') }}" alt=""></button></a>
                    </form>
                @endif
            </div>
            <div class="notiBox">
                @foreach ($notifications as $n)
                    <form action="{{ route('notification.mark-read', $n->id) }}" method="post">
                        @csrf
                        <div class="noti" onclick="this.closest('form').submit()">
                            @switch($n->type)
                                @case('created')
                                    <h3>A {{ $n->table }} was created</h3>
                                    @break
                                @case('updated')
                                    <h3>A {{ $n->table }} was updated</h3>
                                    @break
                                @case('deleted')
                                    <h3>A {{ $n->table }} was deleted</h3>
                                    @break
                                @case('invited')
                                    <h3>You have been invited to a project</h3>
                                    @break
                                @case('removed')
                                    <h3>You have been removed from a project</h3>
                                    @break
                                @break
                            @endswitch
                            <p>{{ \Carbon\Carbon::parse($n->created_at)->translatedFormat('l, F jS Y \a\t H:i') }}</p>
                            @if ($n->is_read == false)
                                <span class="point"></span>
                            @endif
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
        <div class="item">
            @if ($opened_noti == null)
                <p>Click on a notification on the left to open it.</p>
            @else
                <div class="actions">
                    <div style="display: flex; gap: 6px;">
                        @if ($opened_noti->is_read == false)
                            <form action="{{ route('notification.mark-read', $opened_noti->id) }}" method="post">
                                @csrf
                                <button title="Mark as Read"><img class="icon" src="{{ asset('Images/Icons/Actions/Seen.png') }}" alt=""></button>
                            </form>
                        @else
                            <form action="{{ route('notification.mark-unread', $opened_noti->id) }}" method="post">
                                @csrf
                                <button title="Mark as Unread"><img class="icon" src="{{ asset('Images/Icons/Actions/Unseen.png') }}" alt=""></button>
                            </form>
                        @endif
                        <form action="{{ route('notification.delete', $opened_noti->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button title="Delete"><img class="icon" src="{{ asset('Images/Icons/Actions/DeleteTrash.png') }}" alt=""></button>
                        </form>
                    </div>
                    <div>
                        <a href="/dashboard/notifications"><button title="Close Notification"><img class="icon" src="{{ asset('Images/Icons/Actions/Close.png') }}" alt=""></button></a>
                    </div>
                </div>
                <div class="notiDetails">

                    

                    @if ($opened_noti->type == 'invited')
                        @if ($opened_noti->user == null)
                            <h3>A Deleted User as {{ $opened_noti->type }} you to the Project "{{ $opened_noti->message }}"</h3>                          
                        @else
                            <h3><a href="{{ route('profile.overview', $opened_noti->user->id) }}" class="username">{{ $opened_noti->user->name }}</a> as {{ $opened_noti->type }} you to the Project "{{ $opened_noti->message }}"</p>                            
                        @endif

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
                    @elseif ($opened_noti->type == 'removed')

                    @elseif ($opened_noti->type == 'changed_role')

                    @elseif ($opened_noti->type == 'left')

                    @else
                        @if ($opened_noti->user == null)
                            <p>A Deleted User as {{ $opened_noti->type }} the {{ $opened_noti->table }}  "{{ $opened_noti->message }}"</p>                          
                        @else
                            <p><a href="{{ route('profile.overview', $opened_noti->user->id) }}" class="username">{{ $opened_noti->user->name }}</a> as {{ $opened_noti->type }} this {{ $opened_noti->entity_type }}</p>                            
                        @endif
                    @endif

                </div>
            @endif
        </div>
    </div>
@endsection