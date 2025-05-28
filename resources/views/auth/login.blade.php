@section('title')
Login
@endsection
<x-guest-layout>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label>Email</label>
            <input type="email" name="email" id="email">
            <x-input-error :messages="$errors->get('email')"/>
        </div>

        <div>
            <label>Password</label>
            <x-password-input 
                :id="'password'" 
                :name="'password'" 
                :autocomplete="'current_password'" 
                :visible="'visible1'"
                :invisible="'invisible1'"
            />
            <x-input-error :messages="$errors->get('password')"/>
        </div>

        <div class="remember">
            <label>
                <input id="remember_me" type="checkbox" name="remember">
                <span>Remember me</span>
            </label>
        </div>

        <div class="options">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            @endif

            <input type="submit" value="Log In" class="submit">
        </div>
    </form>
</x-guest-layout>
