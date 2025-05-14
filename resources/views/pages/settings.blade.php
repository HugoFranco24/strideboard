@extends('layouts.sidemenu')
@section('title')
    Settings
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/settings.css') }}">
@endsection

@section('body-title')
    Settings
@endsection

@section('body')
    <div class="box">
        <h2>Theme Settings</h2>
        
        <div class="radio">
            <input type="radio" name="theme" id="light"/>
            <label for="light">
              <img src="{{ asset('Images/Dashboard/settings/light.jpg') }}" alt="Light Theme" />
            </label>
          
            <input type="radio" name="theme" id="dark"/>
            <label for="dark">
              <img src="{{ asset('Images/Dashboard/settings/dark.jpg') }}" alt="Dark Theme" />
            </label>
          
            <input type="radio" name="theme" id="system"/>
            <label for="system">
              <img src="{{ asset('Images/Dashboard/settings/system.jpg') }}" alt="System Theme" />
            </label>
        </div>

        <div class="radio_text">
            <label>Light Mode</label>

            <label>Dark Mode</label>

            <label>Match System</label>
        </div>
    </div>
    <div class="box">
        <h2>Menu Settings</h2>
        
        <div class="radio">
            <input type="radio" name="menuState" id="not_collapsed"/>
            <label for="not_collapsed">
              <img src="{{ asset('Images/Dashboard/settings/not_collapsed.jpg') }}" alt="Light Theme" />
            </label>
          
            <input type="radio" name="menuState" id="collapsed"/>
            <label for="collapsed">
              <img src="{{ asset('Images/Dashboard/settings/collapsed.jpg') }}" alt="Dark Theme" />
            </label>
        </div>

        <div class="radio_text">
            <label>Not Collapsed</label>

            <label>Collapsed</label>
        </div>
    </div>
@endsection
