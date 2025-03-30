@extends('layouts.app')

@section('content')
    <div class="w-screen h-screen flex  justify-center items-center bg-gray-200">
        <form action="{{ route('register') }}" method="POST"
            class="w-full max-w-sm border border-primary space-y-6 bg-white p-6 rounded-lg shadow-lg">
            @csrf

            <h2 class="text-2xl font-bold text-dark text-center mb-8">Register<span class="text-primary">!</span></h2>

            {{-- Reusable Classes --}}
            @php
                $inputClasses = 'peer bg-transparent py-3 w-full rounded-md text-gray-700 placeholder-transparent ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none';
                $labelClasses = 'absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-inherit mx-1 px-1 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm transition-all';
            @endphp

            @foreach ([
                'name' => 'Enter name', 
                'email' => 'Enter email', 
                'password' => 'Enter password', 
                'password_confirmation' => 'Confirm password'
                ] as $name => $label)
                    <div class="relative bg-inherit">
                        <input 
                            type="{{ $name === 'password' || $name === 'password_confirmation' ? 'password' : ($name === 'email' ? 'email' : 'text') }}" 
                            name="{{ $name }}"
                            placeholder="{{ $label }}" 
                            value="{{ old($name)}}"
                            class="{{ $inputClasses }}">
                        <label class="{{ $labelClasses }}"> {{ $label }}</label>

                        <p class="text-secondary text-sm min-h-[20px]">
                            @error($name)
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
            @endforeach

            <div class="flex flex-col items-center gap-3">
                <button type="submit"
                    class="mt-2 w-full bg-primary hover:bg-white hover:text-primary border-1 hover:border-primary text-white font-medium py-2 rounded-lg transition duration-300">
                    Register
                </button>
                <div class="text-[16px]">
                    <span class="text-gray-400">Already have an account?</span>
                    <a href="{{ route('login') }}">
                        <span class="hover-underline-hyperlink text-[16px]">
                            Log <span class="text-primary">in</span>
                        </span>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
