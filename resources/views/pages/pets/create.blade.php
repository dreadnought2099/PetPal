@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Add a Pet</h2>

        <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif


            @php
                $inputClasses =
                    'peer py-3 w-full placeholder-transparent rounded-md text-gray-700  ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none';
                $labelClasses =
                    'absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md';
            @endphp

            <label class="block font-semibold">Pet's Name</label>
            <input type="text" name="name" class="w-full p-2 border rounded mb-4" required>

            <label class="block font-semibold">Age (in months/years)</label>
            <input type="number" name="age" class="w-full p-2 border rounded mb-4" required>

            <label class="block font-semibold">Breed</label>
            <input type="text" name="breed" class="w-full p-2 border rounded mb-4" required>

            <label class="block font-semibold">Enter Known Allergies (or type "None")</label>
            <input type="text" name="allergies" class="w-full p-2 border rounded mb-4" required>

            <label class="block font-semibold">Pet Profile (Upload Image)</label>
            <input type="file" name="profile" class="w-full p-2 border rounded mb-4" accept="image/*">

            <label class="block font-semibold">Sex</label>
            <select name="sex" class="w-full p-2 border rounded mb-4" required>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>

            <label class="block font-semibold">Species</label>
            <select name="species" class="w-full p-2 border rounded mb-4" required>
                <option value="0">Dog</option>
                <option value="1">Cat</option>
            </select>

            <label class="block font-semibold">Vaccination Status</label>
            <select name="vaccination" class="w-full p-2 border rounded mb-4" required>
                <option value="0">None</option>
                <option value="1">Partially</option>
                <option value="3">Fully</option>
            </select>

            <label class="block font-semibold">Spayed/Neutered</label>
            <div class="flex space-x-4 mb-4">
                <label><input type="radio" name="spayed_neutered" value="0" required> Yes</label>
                <label><input type="radio" name="spayed_neutered" value="1" required> No</label>
            </div>

            <div class="flex justify-between mt-4">
                <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
            </div>
        </form>
    </div>
@endsection
