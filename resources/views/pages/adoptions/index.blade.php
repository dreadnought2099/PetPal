@extends('layouts.app')

@section('content')
    @php
        $selectedPet = $selectedPet ?? null;
        $pets = $pets ?? collect();
    @endphp

    <div
        class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">
        <h1 class="flex gap-[5px] sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center">
            Apply for
            <span class="text-primary"> Adoption
            </span>
        </h1>

        {{-- Session Message --}}
        <div id="success-message-container" class="absolute top-24 right-4 z-10">
            @if (session('success') || session('error'))
                <div id="message"
                    class="p-3 rounded-md shadow-lg border-l-4
                  {{ session('success') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ session('success') ?? session('error') }}
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
        <form action="{{ route('adopt.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg px-8 pt-6 pb-8 mb-4 space-y-6 z-9">
            @csrf

            @php
                $inputClasses =
                    'peer py-3 w-full placeholder-transparent rounded-md text-gray-700  ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none';
                $labelClasses =
                    'absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md';
            @endphp
            
            <div class="p-3 bg-gray-100 rounded-md">
                <label class="font-semibold">Select Pet</label>
                <select name="pet_id" id="petSelect" class="w-full border p-2 rounded mt-2" required>
                    <option value="" disabled {{ empty($selectedPet) ? 'selected' : '' }}>Choose a pet</option>
                    @foreach ($pets as $pet)
                        <option value="{{ $pet->id }}"
                            {{ isset($selectedPet) && $selectedPet->id == $pet->id ? 'selected' : '' }}
                            {{ $pet->status === 'adopted' ? 'disabled' : '' }}>
                            {{ $pet->name }} - {{ $pet->breed }}
                        </option>
                    @endforeach
                </select>
            </div>

            @foreach (['last_name' => 'Last Name', 'first_name' => 'First Name', 'middle_name' => 'Middle Name', 'address' => 'Address', 'contact_number' => 'Contact Number', 'dob' => 'Date of Birth'] as $name => $label)
                <div class="relative bg-inherit">
                    <input type="{{ $name == 'dob' ? 'date' : 'text' }}" name="{{ $name }}"
                        class="{{ $inputClasses }}" {{ $name !== 'middle_name' ? 'required' : '' }}
                        placeholder="{{ $label }}" value="{{ old($name) }}">
                    <label class="{{ $labelClasses }}">{{ $label }}</label>
                </div>
            @endforeach
            
            <div class="relative bg-inherit">
                <label class="{{ $labelClasses }}">Upload Valid ID (JPEG, PNG, JPG, PDF)</label>
                <input type="file" name="valid_id"
                    class="{{ $inputClasses }}"
                    accept=".jpeg,.png,.jpg,.pdf" required>
            </div>
            @if ($errors->has('valid_id'))
                <p class="text-red-500 text-xs mt-1">{{ $errors->first('valid_id') }}</p>
            @endif
            
            
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
                    <label><input type="radio" name="{{ $name }}" value="yes" required> Yes</label>
                    <label class="ml-4"><input type="radio" name="{{ $name }}" value="no" required>
                        No</label>
                </fieldset>
            @endforeach

            <div class="flex space-y-4 md:flex-row  md:space-x-4 md:space-y-0">
                {{-- Add Button --}}
                <button type="submit"
                    class="border-1 hover:border-primary bg-primary hover:bg-white hover:text-primary cursor-pointer text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out">
                    Apply Now
                </button>
                {{-- Back Button --}}
                <a href="{{ url()->previous() }}"
                    class="border-1 hover:border-primary bg-white hover:bg-white hover:text-primary text-dark font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out">
                    Back
                </a>
            </div>
        </form>
    </div>
@endsection
