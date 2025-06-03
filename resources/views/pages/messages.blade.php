@extends('layouts.main')

@section('title')
    Messages
@endsection

@section('body-title')
    Messages
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/messages.css') }}">
@endsection


@section('body')
  
    <table class="UsersTable">
        <tr>
            <th>Pfp</th>
            <th>Name</th>
            <th>Email</th>
            <th>Options</th>
        </tr>
        @foreach ($userAll as $u)
            <tr>
                <td><img src="{{ asset($u->pfp ?? 'Images/Pfp/pfp_default.png') }}"></td>
                <td><a href="{{ route('profile.overview', $u->id) }}" class="username">{{ $u->name }}</a></td>
                <td>{{ $u->email }}</td>
                <td>Ainda nada</td>
            </tr>
        @endforeach
    </table>
    
@endsection