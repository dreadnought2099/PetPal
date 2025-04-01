@extends('layouts.app')

@section('content')
    <div class="text-center mt-4">
        <h2 class="text-3xl font-bold text-dark">Our <span class="text-primary">Pets</span></h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8 cursor-pointer">
        @foreach ($pets as $pet)
            <!-- Pet Card -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <img src="{{ $pet->pet_profile_path ? asset('storage/' . $pet->pet_profile_path) : asset('images/LRM_20240517_192913-01.jpeg') }}"
                    alt="{{ $pet->name }}" class="w-full h-48 object-cover rounded-lg">
                <h3 class="text-xl font-bold mt-4">{{ $pet->name }}</h3>
                <p class="text-gray-600">{{ $pet->breed }}</p>

                <!-- Open Modal Button -->
                <button onclick='openModal(@json($pet))' class="mt-4 text-primary hover-underline-hyperlink cursor-pointer">See
                    more</button>
            </div>
        @endforeach
    </div>

    <!-- 🔹 Modal Overlay (Blur Background) -->
    <div id="petModalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>

    <!-- 🔹 Pet Details Modal -->
    <div id="petModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg relative">
            <!-- Close Button -->
            <button onclick="closeModal()"
                class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>

            <!-- Pet Image -->
            <img id="petImage" src="" alt="Pet Image" class="w-full h-48 object-cover rounded-lg mb-4">

            <!-- Pet Name -->
            <h2 class="text-2xl font-bold text-center mb-4" id="petName"></h2>

            <!-- Pet Details -->
            <p><strong>Breed:</strong> <span id="petBreed"></span></p>
            <p><strong>Age:</strong> <span id="petAge"></span> years</p>
            <p><strong>Sex:</strong> <span id="petSex"></span></p>
            <p><strong>Species:</strong> <span id="petSpecies"></span></p>
            <p><strong>Allergies:</strong> <span id="petAllergies"></span></p>
            <p><strong>Vaccination Status:</strong> <span id="petVaccination"></span></p>
            <p><strong>Spayed/Neutered:</strong> <span id="petSpayedNeutered"></span></p>
            <p class="mt-3"><strong>Description:</strong></p>
            <p id="petDescription" class="border p-2 rounded bg-gray-100"></p>

            <!-- Buttons -->
            <div class="flex justify-between mt-4">
                <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded cursor-pointer">Close</button>
                @if (auth()->guest() || auth()->user()->hasRole('Adopter'))
                    <a id="adoptNowBtn" href="{{ route('adoption.apply', ['pet_id' => $pet->id]) }}"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Adopt Now
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 🔹 JavaScript to Handle Modal -->
    <script>
        function openModal(pet) {
            // Set modal content
            document.getElementById('petName').innerText = pet.name;
            document.getElementById('petBreed').innerText = pet.breed;
            document.getElementById('petAge').innerText = pet.age;
            document.getElementById('petSex').innerText = pet.sex === 'M' ? 'Male' : 'Female';
            document.getElementById('petSpecies').innerText = pet.species;
            document.getElementById('petAllergies').innerText = pet.allergies || 'None';
            document.getElementById('petVaccination').innerText = pet.vaccination ? 'Fully Vaccinated' : 'Not Vaccinated';
            document.getElementById('petSpayedNeutered').innerText = pet.spayed_neutered ? 'Yes' : 'No';
            document.getElementById('petDescription').innerText = pet.description || 'No description available.';

            // Set pet image
            let petImage = document.getElementById('petImage');
            petImage.src = pet.pet_profile_path ? `/storage/${pet.pet_profile_path}` :
                '/images/LRM_20240517_192913-01.jpeg';

            // Set adoption link (passing pet_id)
            let adoptNowBtn = document.getElementById('adoptNowBtn');
            if (adoptNowBtn) {
                adoptNowBtn.href = `/adoption/apply?pet_id=${pet.id}`;
            } else {
                console.warn("Adopt Now button not found.");
            }

            // Show modal & overlay
            document.getElementById('petModalOverlay').classList.remove('hidden');
            document.getElementById('petModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('petModalOverlay').classList.add('hidden');
            document.getElementById('petModal').classList.add('hidden');
        }
    </script>
@endsection
