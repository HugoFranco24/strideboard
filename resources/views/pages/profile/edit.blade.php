@extends('layouts.main')
@section('title')
    Profile
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/profile.css') }}">
@endsection

@section('body-title')
    Profile
@endsection

@section('body')
    
    @if(session('status'))
        <x-session-status
            :message="session('status')"
        />
    @endif
    
    <div class="box">
        <div class="top">
            <div class="left">
                <img src="{{ asset(auth()->user()->pfp ?? 'Images/Pfp/pfp_default.png') }}" alt="" width="150px" height="150px" class="pfp">
                <div style="display: block; align-items: center">
                    <h3 class="SQL" style="margin: 30px 0px 0px 10px; font-size: 22px; font-weight:600; transform:translateY(-100%)">{{ auth()->user()->name }}</h3>
                    <p class="SQL" style=" margin: 0px 10px 0px 10px; transform:translateY(-100%); color: var(--text-color);">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <h2 style="margin-top: 40px">Details</h2>
        <p>Joined on {{ \Carbon\Carbon::parse(auth()->user()->created_at)->format('d F Y \a\t H:i') }}</p>
        @if (auth()->user()->email_verified_at != '')
            <p>Verified</p>
        @else
            <p>Not Verified</p>
        @endif
    </div>
    
    <div class="box">
        <div>
            <h2>Profile Picture</h2>
            <form action="{{ route('profile.uploadImg') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Change Profile Picture</label>
                <br>
                <input type="file" name="pfp" accept="image/*" required onchange="this.form.submit();">
                <x-input-error :messages="$errors->get('pfp')" />
            </form>

            <div class="lineSpace"></div>

            <h2>Personal Information</h2>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('patch')
                <label>Name</label>
                <br>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" style="margin-bottom: 10px">
                <x-input-error :messages="$errors->get('name')" />
                <br>
                
                <label>Email</label>
                <br>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                <x-input-error :messages="$errors->get('email')" />
                <br>
                <button type="submit" class="btn_default">Keep Changes</button>
            </form>
        </div>
    </div>
    
    <div class="box">
        <h2>Update Password</h2>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('put')
            <label>Current Password</label>
            <br>
            <input type="password" name="current_password" value="" autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')"/>

            <br>

            <label>New Password</label>
            <br>
            <input type="password" name="password" value="">
            <x-input-error :messages="$errors->updatePassword->get('password')" autocomplete="new-password"/>
            <br>
            <label>Confirm New Password</label>
            <br>

            <input type="password" name="password_confirmation" value="">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" autocomplete="new-password"/>
            <br>

            <button type="submit" class="btn_default">Change Password</button>
        </form>
        
    </div>

    <div class="box">
        <h2>Delete Account</h2>

        <p>
            Unhappy with your account or don't want to use it anymore? Click in the button below to delete your account. 
            <br>
            Once you delete your account, you will lose all projects that your were and you will lose all data in this account.
        </p>
        <button type="submit" class="btn_default" onclick="toogleModal()">Delete Account</button>
    </div>

    {{-- Modal --}}
    <div class="modal" id="modal" style="display: none">
        <div class="modal_content" id="modal_content">
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('delete')
                            
                <h3 style="font-size: 22px;">Are you sure you want to delete your account?</h3>
                <button type="submit" class="btn_delete">Yes, delete account</button>
                <button type="button" class="btn_cancel" onclick="toogleModal()">No, I changed my mind</button>
            </form>
        </div>
    </div>
    {{-- Modal END --}}
    
    
@endsection

<script>
    function toogleModal() {
        var modal = document.getElementById("modal");
        var modal_content = document.getElementById("modal_content");

        if(modal.style.display == "none")
            modal.style.display = "block";
        else{
            modal.style.display = "none";
        } 
    }
</script>