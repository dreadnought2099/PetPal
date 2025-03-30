@extends('layouts.app')

@section('content')
    <div
        class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">

        <h1 class="flex gap-[5px] sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10
        ">Add <span
                class="text-primary"> Record</span></h1>

        {{-- Form --}}
        <form action="{{ route('records.store') }}" method="POST" class="rounded-lg px-8 pt-6 pb-8 mb-4 space-y-6">
            @csrf

            {{-- Define reusable input field component --}}
            @php
                $inputClasses =
                    'peer py-3 w-full placeholder-transparent rounded-md text-gray-700  ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none';
                $labelClasses =
                    'absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md';

            @endphp



            @foreach (['title' => 'Title', 'author' => 'Author', 'publication_year' => 'Publication Year', 'category' => 'Category', 'isbn' => 'ISBN'] as $name => $label)
                <div class="relative bg-inherit">
                    <input type="{{ $name === 'publication_year' ? 'number' : 'text' }}" name="{{ $name }}"
                        placeholder="{{ $label }}" value="{{ old($name) }}"
                        class="{{ $inputClasses }} @error($name) ring-primary @enderror">
                    <label class="{{ $labelClasses }}">{{ $label }}</label>

                    {{-- Reserve space for error messages --}}
                    <p class="text-primary text-sm min-h-[20px]">
                        @error($name)
                            {{ $message }}
                        @enderror
                    </p>
                </div>
            @endforeach


            {{-- Buttons --}}
            <div class="flex flex-col space-y-4 md:flex-row  md:space-x-4 md:space-y-0">
                {{-- Add Button --}}
                <button type="submit"
                    class="border-1 hover:border-primary bg-dark hover:bg-white hover:text-primary text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out   ">
                    Add record
                </button>

                {{-- Back Button --}}
                <a href="{{ route('records.index') }}"
                    class="border-1 hover:border-primary bg-white hover:bg-white hover:text-primary text-dark font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out">
                    Back
                </a>
            </div>
            @if (session('success') || session('error'))
                <div
                    class="min-h-[50px] p-3 rounded mb-3 text-white {{ session('success') ? 'bg-green-500' : 'bg-primary' }}">
                    {{ session('success') ?? session('error') }}
                </div>
            @endif
        </form>
    </div>
@endsection
