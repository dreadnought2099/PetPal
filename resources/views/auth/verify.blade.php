@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-semibold text-center mb-4">Verify Your Email Address</h2>

    @if (session('resent'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <p class="text-gray-600 text-center mb-4">
        Before proceeding, please check your email and verify your account.
    </p>

    <form class="text-center" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-white hover:text-blue-500 border-1 border-blue-500 transition-all duration-300 ease-in-out cursor-pointer">
            Resend Verification Email
        </button>
    </form>
</div>
@endsection