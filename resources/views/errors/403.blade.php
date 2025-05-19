@extends('errors::minimal')

@section('title', __('403'))
@section('error')
    403
@endsection
@section('img')
    <img src="{{ asset('Images/Errors/403.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('img-dark')
    <img src="{{ asset('Images/Errors/403-Dark.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('desc')
    <h2>Forbidden, Trying to be sneaky?</h2>
    <p>You do not have the permissions for this restricted area!</p>
    <p>I'm going to call the police if you don't leave now!</p>
@endsection
