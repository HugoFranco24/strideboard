@extends('errors::minimal')

@section('title', __('402'))
@section('error')
    402
@endsection
@section('img')
    <img src="{{ asset('Images/Errors/402.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('img-dark')
    <img src="{{ asset('Images/Errors/402-Dark.jpg') }}" alt="" width="100%" height="100%">
@endsection
@section('desc')
    <h2>Payment Required, show me the money! - 402</h2>
    <p>I need to buy coffee and for that you gotta pay to go to this page...</p>
@endsection
