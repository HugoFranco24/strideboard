@section('title')
Confirm Password
@endsection
<x-guest-layout>
    <div style="text-align: center">
        This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label>Password</label>
            <x-password-input
                :id="'password'" 
                :name="'password'" 
                :autocomplete="'current-password'" 
                :visible="'visible1'"
                :invisible="'invisible1'"
            >

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="options">
            <button type="submit" class="submit" style="width:100%">Confirm</button>
        </div>
    </form>
</x-guest-layout>
