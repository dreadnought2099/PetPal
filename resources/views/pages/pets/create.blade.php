@extends('layouts.app')

@section('content')
    <div
        class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">
        <h2 class="flex gap-1 sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center">
            Add a <span class="text-primary">Pet</span>
        </h2>

        <form action="{{ route('pets.store') }}" method="POST"
            onsubmit="this.querySelector('button[type=submit]').disabled = true;" enctype="multipart/form-data"
            class="px-8 pt-6 pb-8 space-y-6">
            @csrf

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded">{{ session('error') }}</div>
            @endif

            @php
                $inputClasses =
                    'peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary outline-none';
                $labelClasses =
                    'absolute left-3 -top-3 text-sm text-gray-600 bg-white px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-3 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:px-2';
                $textClass =
                    'peer py-3 w-full rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary outline-none';
            @endphp

            {{-- Text Inputs --}}
            @foreach ([
            'name' => "Pet's Name",
            'age' => 'Age (in years)',
            'breed' => 'Breed',
            'allergies' => 'Enter Known Allergies (or type "None")',
        ] as $name => $label)
                <div class="relative">
                    <input type="{{ $name === 'age' ? 'number' : 'text' }}" name="{{ $name }}"
                        value="{{ old($name) }}" class="{{ $inputClasses }}" placeholder=" " required>
                    <label class="{{ $labelClasses }}">{{ $label }}</label>
                </div>
            @endforeach

            {{-- Description --}}
            <div class="relative">
                <textarea name="description" rows="4" class="{{ $textClass }}"
                    placeholder="Write a short description about the pet...">{{ old('description') ?? '' }}</textarea>
            </div>

            {{-- File Upload --}}
            <div class="relative">
                <input type="file" name="profile" class="{{ $inputClasses }}" accept="image/*">
                <label class="{{ $labelClasses }}">Pet Profile (Upload Image)</label>
            </div>

            {{-- Select Inputs --}}
            @foreach ([
            'sex' => ['M' => 'Male', 'F' => 'Female'],
            'species' => ['0' => 'Dog', '1' => 'Cat'],
            'vaccination' => [0 => 'None', 1 => 'Partially', 2 => 'Fully'],
        ] as $name => $options)
                <div class="relative">
                    <select name="{{ $name }}" class="{{ $inputClasses }}" required>
                        @foreach ($options as $key => $value)
                            <option value="{{ $key }}" {{ old($name) == (int) $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <label class="{{ $labelClasses }}">{{ ucfirst($name) }}</label>
                </div>
            @endforeach

            {{-- Spayed/Neutered --}}
            <div class="relative">
                <span class="block text-gray-700 font-medium mb-2">Spayed/Neutered</span>
                <div class="flex space-x-4">
                    @foreach (['1' => 'Yes', '0' => 'No'] as $value => $text)
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="spayed_neutered" value="{{ $value }}"
                                {{ old('spayed_neutered') == $value ? 'checked' : '' }} required>
                            <span>{{ $text }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex space-x-4">
                <button type="submit"
                    class="border border-primary bg-primary text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:bg-white hover:text-primary hover:border-primary duration-300 cursor-pointer">
                    Add
                </button>
                <a href="{{ url()->previous() }}"
                    class="border border-dark hover:border-primary bg-white text-dark font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:text-primary duration-300">
                    Back
                </a>
            </div>
        </form>
    </div>
@endsection
