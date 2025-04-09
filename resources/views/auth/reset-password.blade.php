@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700 text-center mb-4">
            Reset Your Password
        </h2>

        <p class="text-sm text-gray-600 text-center mb-6">
            Enter your new password below to reset your account.
        </p>

        {{-- @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded-md mb-4">
                {{ session('status') }}
            </div>
        @endif --}}

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            {{-- Input fields for password and confirmation --}}
            @php
                $inputClasses = 'peer block w-full bg-transparent border border-gray-300 rounded-md px-4 py-3 text-gray-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none placeholder-transparent';
                $labelClasses = 'absolute left-3 top-3 text-gray-500 text-sm bg-white px-1 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-xs peer-focus:text-primary peer-focus:px-1 transition-all';
            @endphp

        @foreach ([
            'password' => 'Enter new password',
            'password_confirmation' => 'Confirm new password'
        ] as $name => $label)
            <div class="bg-inherit relative">
                <input type="password"
                    name="{{ $name }}"
                    placeholder="{{ $label }}"
                    value="{{ old($name) }}"
                    class="{{ $inputClasses }}">

                <label class="{{ $labelClasses }}">
                    {{ $label }}
                </label>

                <p class="text-primary text-sm min-h-[20px]">
                    @error($name)
                        {{ $message }}
                    @enderror
                </p>
            </div>
        @endforeach

            <button type="submit"
                class="mt-2 w-full bg-primary hover:bg-white hover:text-primary border-1 hover:border-primary text-white font-medium py-2 rounded-lg transition duration-300">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
