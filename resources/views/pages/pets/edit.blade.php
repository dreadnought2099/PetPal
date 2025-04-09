@extends('layouts.app')

@section('content')
    <div
        class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">
        <h2 class="flex gap-1 sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center">Edit Pet <span
                class="text-primary">Listing</span></h2>

        <!-- Edit Pet Form -->
        <form action="{{ route('pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data"
            class="px-8 pt-6 pb-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="profile" class="block text-sm font-medium text-gray-700">Profile Image</label>
                <input type="file" id="profile" name="profile"
                    class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                @if ($pet->pet_profile_path)
                    <img src="{{ asset('storage/' . $pet->pet_profile_path) }}" alt="Current Profile Image"
                        class="mt-2 w-140 h-auto object-cover rounded-md">
                @endif
            </div>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Pet Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $pet->name) }}"
                    class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>

            <div class="mb-4">
                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                <input type="number" id="age" name="age" value="{{ old('age', $pet->age) }}"
                    class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>

            <div class="mb-4">
                <label for="breed" class="block text-sm font-medium text-gray-700">Breed</label>
                <input type="text" id="breed" name="breed" value="{{ old('breed', $pet->breed) }}"
                    class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>

            <div class="mb-4">
                <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                <select name="sex" id="sex" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    <option value="M" {{ old('sex', $pet->sex) === 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('sex', $pet->sex) === 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="species" class="block text-sm font-medium text-gray-700">Species</label>
                <select name="species" id="species" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    <option value="{{ \App\Models\Pet::SPECIES_DOG }}"
                        {{ (int) old('species', $pet->species) === \App\Models\Pet::SPECIES_DOG ? 'selected' : '' }}>Dog
                    </option>
                    <option value="{{ \App\Models\Pet::SPECIES_CAT }}"
                        {{ (int) old('species', $pet->species) === \App\Models\Pet::SPECIES_CAT ? 'selected' : '' }}>Cat
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label for="vaccination" class="block text-sm font-medium text-gray-700">Vaccination Status</label>
                <select name="vaccination" id="vaccination" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    <option value="{{ \App\Models\Pet::VACCINATION_NONE }}"
                        {{ (int) old('vaccination', $pet->vaccination) === \App\Models\Pet::VACCINATION_NONE ? 'selected' : '' }}>
                        None</option>
                    <option value="{{ \App\Models\Pet::VACCINATION_PARTIAL }}"
                        {{ (int) old('vaccination', $pet->vaccination) === \App\Models\Pet::VACCINATION_PARTIAL ? 'selected' : '' }}>
                        Partially</option>
                    <option value="{{ \App\Models\Pet::VACCINATION_FULL }}"
                        {{ (int) old('vaccination', $pet->vaccination) === \App\Models\Pet::VACCINATION_FULL ? 'selected' : '' }}>
                        Fully</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="spayed_neutered" class="block text-sm font-medium text-gray-700">Spayed/Neutered</label>
                    <input type="hidden" id="spayed_neutered" name="spayed_neutered"
                        {{ old('spayed_neutered', $pet->spayed_neutered) ? 'checked' : '' }} value="0">
                    <input type="checkbox" id="spayed_neutered" name="spayed_neutered"
                        {{ old('spayed_neutered', $pet->spayed_neutered) ? 'checked' : '' }} value="1"
                        {{ $pet->spayed_neutered ? 'checked' : '' }} class="mt-1 p-2 border border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
                <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies</label>
                <input type="text" id="allergies" name="allergies" value="{{ old('allergies', $pet->allergies) }}"
                    class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="mt-1 p-2 border border-gray-300 rounded-md w-full">{{ old('description', $pet->description) }}</textarea>
            </div>

            <div class="flex space-x-4">
                <button type="submit"
                    class="border border-primary bg-primary text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:bg-white hover:text-primary hover:border-primary duration-300 cursor-pointer">
                    Update
                </button>
                <a href="{{ url()->previous() }}"
                    class="border border-dark hover:border-primary bg-white text-dark font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:text-primary duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
