@extends('layouts.main')
@section('title')
    Profile - {{ $OVuser->name }}
@endsection

@section('go-back')
    <a class="goBack" onclick="history.back()">
        <img class="icon" width="35" height="35" src="{{ asset('Images/Icons/Menu/Go-back.png') }}" alt="undo" title="Go Back"/>
    </a>
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/profile.css') }}">
@endsection

@section('body-title')
    Profile Overview
@endsection

@section('body')
    <div class="box">
        <div class="top">
            <div class="left">
                <img src="{{ asset($OVuser->pfp) }}" alt="" width="150px" height="150px" class="pfp">
                <div style="display: block; align-items: center">
                    <h3 class="SQL" style="margin: 30px 0px 0px 10px; font-size: 22px; font-weight:600; transform:translateY(-100%)">{{ $OVuser->name }}</h3>
                    <p class="SQL" style=" margin: 0px 10px 0px 10px; transform:translateY(-100%); color: var(--text-color);">{{ $OVuser->email }}</p>
                </div>
            </div>
        </div>
        
        <div class="lineSpace"></div>

        <div class="Details">
            <h2>Details</h2>
            <p>Joined on {{ \Carbon\Carbon::parse($OVuser->created_at)->format('d F Y \a\t H:i') }}</p>
            @if ($OVuser->email_verified_at != '')
                <p>Verified</p>
            @else
                <p>Not Verified</p>
            @endif
            <p>Projects in Commun: {{$communProjects->count() == 0 ? 'None' : ''}}</p>
            @foreach ($communProjects as $p)
                <a href="/dashboard/projects/overview/{{ $p->id }}" title="Overview" class="overview">
                    <div class="project" style="--project-bar-color: {{ $p->color }}">
                        <div style="margin-left: 25px;">
                            <h4>{{ $p->name }}</h4>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection