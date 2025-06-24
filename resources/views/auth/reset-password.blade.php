@section('title')
Reset Password
@endsection
<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')"/>
        </div>

        <!-- Password -->
        <div>
            <label for="password">Password</label>
            <x-password-input
                :id="'password'" 
                :name="'password'" 
                :autocomplete="'new-password'" 
                :visible="'visible1'"
                :invisible="'invisible1'"
            />            
            <x-input-error :messages="$errors->get('password')"/>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation">Confirm Password</label>
            <x-password-input
                :id="'password_confirmation'" 
                :name="'password_confirmation'" 
                :autocomplete="'new-password'" 
                :visible="'visible2'"
                :invisible="'invisible2'"
            />
            <x-input-error :messages="$errors->get('password_confirmation')"/>
        </div>

        <div class="options">
            <button type="submit" class="submit" style="width: 100%">Reset Password</button>
        </div>
    </form>
</x-guest-layout>
