@extends('errors::minimal')

@section('title', __('500'))
@section('error')
    500
@endsection
@section('img')
    <img src="{{ asset('Images/Errors/500.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('img-dark')
    <img src="{{ asset('Images/Errors/500-Dark.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('desc')
    <h2>Internal Server Error, it's the intern's fault - 500</h2>
    <p>It was all fine until it wasn't. The IT team is working on it.</p>
    <p>Chat GPT says he can't help with this one...</p>
@endsection