@extends('layouts.app')

@section('content')
<div class="min-h-screen w-full flex justify-center items-center px-4 py-6">
    <form action="{{ route('register') }}" method="POST"
        class="bg-white w-[90%] max-w-xs p-4 rounded-lg shadow-lg border border-primary space-y-4">
        @csrf

        <h2 class="text-xl font-bold text-dark text-center">Register<span class="text-primary">!</span></h2>

        @php
            $inputClasses = 'peer bg-transparent py-2 w-full rounded-md text-gray-700 placeholder-transparent ring-1 px-3 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none';
            $labelClasses = 'absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-inherit mx-1 px-1 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm transition-all';
        @endphp

        @foreach ([
            'name' => 'Enter name', 
            'email' => 'Enter email', 
            'password' => 'Enter password', 
            'password_confirmation' => 'Confirm password'
        ] as $name => $label)
            <div class="relative">
                <input 
                    type="{{ str_contains($name, 'password') ? 'password' : ($name === 'email' ? 'email' : 'text') }}" 
                    name="{{ $name }}" 
                    placeholder="{{ $label }}" 
                    value="{{ old($name) }}" 
                    class="{{ $inputClasses }}">
                <label class="{{ $labelClasses }}">{{ $label }}</label>
                {{-- Error message with compact spacing --}}
                <p class="text-secondary text-xs min-h-[16px] mt-1">
                    @error($name)
                        {{ $message }}
                    @enderror
                </p>
            </div>
        @endforeach

        <div class="space-y-3">
            <button type="submit"
                class="w-full bg-primary text-white font-medium py-2 rounded-lg transition hover:bg-white hover:text-primary border hover:border-primary">
                Register
            </button>

            <div class="text-center text-xs pt-1">
                <span class="text-gray-500">Already have an account?</span>
                <a href="{{ route('login') }}" class="text-dark hover:text-primary hover-underline-hyperlink ml-1">Log in</a>
            </div>
        </div>
    </form>
</div>
@endsection
