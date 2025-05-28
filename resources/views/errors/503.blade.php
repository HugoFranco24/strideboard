@extends('errors::minimal')

@section('title', __('503'))
@section('error')
    503
@endsection
@section('img')
    <img src="{{ asset('Images/Errors/503.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('img-dark')
    <img src="{{ asset('Images/Errors/503-Dark.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('desc')
    <h2>Service Unavailable - 503</h2>
    <p>Things are temporarily out of service. We might be updating the server or just sleeping.</p>
    <p>Either way try again later.</p>
@endsection
