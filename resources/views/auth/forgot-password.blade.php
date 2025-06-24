@section('title')
Forgot Password
@endsection
<x-guest-layout>
    <div style="margin-bottom: 20px; text-align: center;">
        Forgot your password?
        <br>
        No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    <!-- Session Status -->
    {{session('status')}}

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" value="Email">
            <input id="email" type="email" name="email" :value="old('email')" placeholder="example@gmail.com" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="options">
            <input type="submit" value="Email Password Reset Link" class="submit" style="width: 100%">
        </div>
    </form>
</x-guest-layout>
