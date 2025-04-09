@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full flex justify-center items-center px-4 py-6">
        <form action="{{ route('register') }}" method="POST"
            class="bg-white w-[90%] max-w-xs p-4 rounded-lg shadow-lg border border-primary space-y-4">
            @csrf

            <h2 class="text-xl font-bold text-dark text-center">Register<span class="text-primary">!</span></h2>

            @php
                $inputClasses =
                    'peer py-3 w-full placeholder-transparent rounded-md text-gray-700  ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none';
                $labelClasses =
                    'absolute cursor-text left-0 -top-2 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md';
            @endphp


            @foreach ([
            'name' => 'Enter name',
            'email' => 'Enter email',
            'password' => 'Enter password',
            'password_confirmation' => 'Confirm password',
        ] as $name => $label)
                <div class="relative">
                    <input type="{{ str_contains($name, 'password') ? 'password' : ($name === 'email' ? 'email' : 'text') }}"
                        name="{{ $name }}" placeholder="{{ $label }}" value="{{ old($name) }}"
                        class="{{ $inputClasses }}">
                    <label class="{{ $labelClasses }}">{{ $label }}</label>
                    <p class="text-secondary text-xs min-h-[14px] mt-1">
                        @error($name)
                            {{ $message }}
                        @enderror
                    </p>
                </div>
            @endforeach

            <div class="space-y-3">
                <button type="submit"
                    class="w-full bg-primary text-white font-medium py-2 rounded-lg transition hover:bg-white hover:text-primary border hover:border-primary cursor-pointer">
                    Register
                </button>

                <div class="text-center text-xs pt-1">
                    <span class="text-gray-500">Already have an account?</span>
                    <a href="{{ route('login') }}" class="text-dark hover:text-primary hover-underline-hyperlink ml-1">Log
                        in</a>
                </div>
            </div>
        </form>
    </div>
@endsection
