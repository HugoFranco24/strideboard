@section('title')
    Register
@endsection

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" onclick="this.disabled=true; this.form.submit();">
        @csrf

        <!-- Name -->
        <div>
            <label>Name</label>
            <input id="name" type="text" name="name" :value="old('name')" required autocomplete="name"/>
            <x-input-error :messages="$errors->get('name')"/>
        </div>

        <!-- Email Address -->
        <div>
            <label>Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')"/>    
        </div>

        <!-- Password -->
        <div>
            <label>Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label>Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')"/>
        </div>

        <div class="options">
            <a href="{{ route('login') }}">Already have an Account</a>

            <input type="submit" class="submit" value="Register">
        </div>
    </form>
</x-guest-layout>
