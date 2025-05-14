@extends('layouts.sidemenu')

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
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>Ainda nada</td>
            </tr>
        @endforeach
    </table>
    
@endsection