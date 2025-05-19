@extends('errors::minimal')

@section('title', __('401'))
@section('error')
    401
@endsection
@section('img')
    <img src="{{ asset('Images/Errors/401.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('img-dark')
    <img src="{{ asset('Images/Errors/401-Dark.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('desc')
    <h2>Unauthorized, you shall not pass!</h2>
    <p>This part of the site requires you to prove you're you. Without the secret password or handshake, this door stays shut.</p>
    <p>Login to access this page!</p>
@endsection
