@extends('errors::minimal')

@section('title', __('404'))
@section('error')
    404
@endsection
@section('img')
    <img src="{{ asset('Images/Errors/404.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('img-dark')
    <img src="{{ asset('Images/Errors/404-Dark.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('desc')
    <h2>This page doesn't exist - 404</h2>
    <p>The page you’re looking for isn’t here. It might have been moved, deleted, or abducted by digital aliens.</p>
    <p>Either way, you’ve officially hit a dead end. Contact us if you need help!</p>
@endsection
