@extends('layouts.app')

@section('content')

    @php
        $selectedPet = $selectedPet ?? null;
        $pets = $pets ?? collect();
    @endphp

    <div
        class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">
        <h1 class="flex gap-[5px] sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center">
            Edit
            <span class="text-primary"> Adoption
            </span>
            Details
        </h1>

        {{-- Display Validation Errors --}}
        <div class="min-h-[50px]">
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <form action="{{ route('adopt.update', $adoption->id) }}" method="POST" class="space-y-4 p-6 mb-6 rounded-lg">
            @csrf
            @method('PUT')

            @php
                $inputClasses =
                    'peer py-3 w-full placeholder-transparent rounded-md text-gray-700  ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none';
                $labelClasses =
                    'absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md';
            @endphp

            <div class="p-3 bg-gray-100 rounded-md">
                <label class="font-semibold">Select Pet</label>
                <select name="pet_id" id="petSelect" class="w-full border p-2 rounded mt-2" required>
                    <option value="" disabled {{ old('pet_id', $adoption->pet_id) == '' ? 'selected' : '' }}>Choose a
                        pet</option>
                    @foreach ($pets as $pet)
                        <option value="{{ $pet->id }}"
                            {{ old('pet_id', $adoption->pet_id) == $pet->id ? 'selected' : '' }}>
                            {{ $pet->name }} - {{ $pet->breed }}
                        </option>
                    @endforeach
                </select>
            </div>

            @foreach (['last_name' => 'Last Name', 'first_name' => 'First Name', 'middle_name' => 'Middle Name', 'address' => 'Address', 'contact_number' => 'Contact Number', 'dob' => 'Date of Birth'] as $name => $label)
                <div class="relative bg-inherit">
                    <input type="{{ $name == 'dob' ? 'date' : 'text' }}" name="{{ $name }}"
                        value="{{ old($name, $adoption->$name) }}" class="{{ $inputClasses }}"
                        {{ $name !== 'middle_name' ? 'required' : '' }} placeholder="{{ $label }}"
                        value="{{ old($name) }}">
                    <label class="{{ $labelClasses }}">{{ $label }}</label>
                </div>
            @endforeach

            <div class="mb-4">
                <div class="relative bg-inherit">
                    <input type="file" id="valid_id" name="valid_id" class="{{ $inputClasses }}"
                        accept=".jpeg,.png,.jpg,.pdf" required>
                    <label for="valid_id" class="{{ $labelClasses }}">Upload Valid ID (JPEG, PNG, JPG, PDF)</label>
                    @if ($adoption->valid_id)
                        <img src="{{ Storage::url($adoption->valid_id) }}" alt="Valid ID"
                            class="mt-2 w-32 h-32 object-cover rounded-md cursor-pointer"
                            onclick="showImageModal('{{ Storage::url($adoption->valid_id) }}')">
                    @endif

                </div>
            </div>

            <div id="image-modal" class="hidden fixed inset-0 flex items-center justify-center z-50 p-6 bg-opacity-75">
                <div class="relative rounded-xl shadow-2xl p-0 bg-transparent max-w-lg">
                    <img id="modal-image" src="" alt="Valid ID"
                        class="w-auto h-auto max-w-full max-h-80 rounded-lg">
                    <button onclick="closeImageModal()"
                        class="absolute -top-9 -right-6 text-gray-500 text-4xl hover:text-red-500 transition cursor-pointer">&times;</button>
                </div>
            </div>

            <style>
                .hidden {
                    display: none;
                }

                #image-modal {
                    transition: opacity 0.3s ease-in-out;
                    opacity: 0;
                }

                #image-modal.show {
                    opacity: 1;
                }

                #image-modal img {
                    transition: transform 0.3s ease;
                }

                #image-modal img:hover {
                    transform: scale(1.05);
                }
            </style>

            @php
                $questions = [
                    'previous_experience' => 'Do you have previous pet ownership experience?',
                    'other_pets' => 'Do you have other pets at home?',
                    'financial_preparedness' => 'Are you financially prepared for pet care?',
                ];
            @endphp

            @foreach ($questions as $name => $question)
                <fieldset class="mb-4">
                    <legend class="font-semibold">{{ $question }}</legend>
                    <label><input type="radio" name="{{ $name }}" value="yes"
                            {{ old($name, $adoption->$name) == 'yes' ? 'checked' : '' }} required> Yes</label>
                    <label class="ml-4"><input type="radio" name="{{ $name }}" value="no"
                            {{ old($name, $adoption->$name) == 'no' ? 'checked' : '' }} required>
                        No</label>
                </fieldset>
            @endforeach



            <div class="flex flex-col space-y-4 md:flex-row  md:space-x-4 md:space-y-0">
                {{-- Add Button --}}
                <button type="submit"
                    class="border-1 hover:border-primary bg-primary hover:bg-white hover:text-primary text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out   ">
                    Save changes
                </button>
                {{-- Back Button --}}
                <a href="{{ url()->previous() }}"
                    class="border-1 hover:border-primary bg-white hover:bg-white hover:text-primary text-dark font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out">
                    Back
                </a>
        </form>
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Script Loaded!');

        // Show Image Modal
        window.showImageModal = function(imageSrc) {
            const modal = document.getElementById('image-modal');
            const modalImage = document.getElementById('modal-image');

            modalImage.src = imageSrc;
            modal.classList.add('show');
            modal.classList.remove('hidden');
        };

        // Close Image Modal
        window.closeImageModal = function() {
            const modal = document.getElementById('image-modal');
            modal.classList.remove('show');
            modal.classList.add('hidden');
        };

        // Close Modal on Outside Click
        document.getElementById('image-modal').addEventListener('click', (event) => {
            if (event.target.id === 'image-modal') {
                closeImageModal();
            }
        });
    });
</script>
