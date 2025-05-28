@extends('errors::minimal')

@section('title', __('429'))
@section('error')
    429
@endsection
@section('img')
    <img src="{{ asset('Images/Errors/429.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('img-dark')
    <img src="{{ asset('Images/Errors/429-Dark.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('desc')
    <h2>Too Many Requests - Please calm down - 429</h2>
    <p>You're clicking too much. Slow down or the server is going to file a restraining order against you!</p>
    <p>Breathe and then try again.</p>
@endsection
