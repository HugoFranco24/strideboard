@extends('errors::minimal')

@section('title', __('419'))
@section('error')
    419
@endsection
@section('img')
    <img src="{{ asset('Images/Errors/419.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('img-dark')
    <img src="{{ asset('Images/Errors/419-Dark.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('desc')
    <h2>Page Expired - 419</h2>
    <p>You were gone for too long and we panicked</p>
    <p>Your session expired while we were having abandonment issues.</p>
@endsection
