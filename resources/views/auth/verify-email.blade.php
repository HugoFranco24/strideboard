@section('title')
Verify Email
@endsection
<x-guest-layout>
    <div style="text-align: center">
        Thanks for signing up!
        <br>
        Before getting started, could you verify your email address by clicking on the link we just emailed to you? 
        <br><br>
        If you didn't receive the email, we will gladly send you another.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="text-align: center">
            <br>
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="options">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout">
                Log Out
            </button>
        </form>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="submit">
                Resend Verification Email
            </button>
        </form>
    </div>
</x-guest-layout>
