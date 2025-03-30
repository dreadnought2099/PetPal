@extends('layouts.app')

@section('content')
    <div class="w-screen h-screen flex flex-col justify-start items-center bg-gray-200 pt-32">

        <div class="absolute top-4 right-4 z-0">
            @if (session('status'))
                <div id="message" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-md w-full text-center">
                    {{ session('status') }}
                </div>

                <script>
                    setTimeout(() => {
                        let messageDiv = document.getElementById('message');
                        if (messageDiv) {
                            messageDiv.style.display = 'none';
                        }
                    }, 4000);
                </script>
            @endif
        </div>

        <form action="{{ route('password.email') }}" method="POST"
            class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm border border-primary space-y-6">
            @csrf

            <h2 class="text-2xl font-bold text-dark text-center mb-6">Forgot <span class="text-primary">Password</span></h2>

            <p class="text-sm text-gray-600 text-center">
                Enter your registered email, and we'll send you a reset link.
            </p>

            @php
                $inputClasses = 'peer bg-transparent py-3 w-full rounded-md text-gray-700 placeholder-transparent ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none';
                $labelClasses = 'absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-inherit mx-1 px-1 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm transition-all';
            @endphp
            
            <div class="relative bg-inherit">
                <input type="email" id="email" name="email" placeholder="email" required
                    class="{{ $inputClasses }}">
                <label for="email"
                    class="{{ $labelClasses }}">
                    Email
                </label>
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-white hover:text-primary border border-primary text-white font-medium py-3 rounded-lg transition duration-300 cursor-pointer">
                Send Reset Link
            </button>

            <div class="text-center">
                <span class="text-primary">or</span>
                <a href="{{ route('login') }}" class="hover-underline-hyperlink">Back to Login</a>
            </div>
        </form>
    </div>
@endsection
