@extends('layouts.app')

@section('content')
    <div class="text-center mt-4">
        <div class="relative w-full">
            {{-- Session Message --}}
            <div id="success-message-container" class="absolute top-4 right-4 z-0">
                @if (session('success') || session('info'))
                    <div id="message"
                        class="p-3 rounded-md shadow-lg border-l-4
                      {{ session('success') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ session('success') ?? session('info') }}
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
            <h2 class="text-3xl font-bold text-dark">Our <span class="text-primary">Pets</span></h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8 cursor-pointer">
            @foreach ($pets as $pet)
                @if ($pet->status !== 'adopted')
                    <!-- Pet Card -->
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <img src="{{ $pet->pet_profile_path ? asset('storage/' . $pet->pet_profile_path) : asset('images/LRM_20240517_192913-01.jpeg') }}"
                            alt="{{ $pet->name }}" class="w-full h-48 object-cover rounded-lg">
                        <h3 class="text-xl font-bold mt-4">{{ $pet->name }}</h3>
                        <p class="text-gray-600">{{ $pet->breed }}</p>

                        <!-- Pet Status: Change starts here -->
                        <p
                            class="text-sm mt-2 font-medium 
                        {{ $pet->status === 'adopted' ? 'text-red-500' : 'text-green-500' }}">
                            Status: {{ ucfirst($pet->status) }}
                        </p>
                        <!-- Pet Status: Change ends here -->

                        <!-- Open Modal Button -->
                        <button onclick='openModal(@json($pet))'
                            class="mt-4 text-primary hover-underline-hyperlink cursor-pointer">
                            See more
                        </button>

                    </div>
                @endif
            @endforeach
        </div>

        <!-- 🔹 Modal Overlay (Blur Background) -->
        <div id="petModalOverlay" class="fixed inset-0 bg-black bg-opacity-10 hidden">
            <!-- 🔹 Pet Details Modal -->
            <div id="petModal" class="flex fixed inset-0 items-center justify-center  z-50">
                <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg relative">
                    <!-- Close Button -->
                    <button onclick="closeModal()"
                        class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl cursor-pointer">&times;</button>

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

                    <!-- Adoption Status: Change starts here -->
                    @if ($pet->status === 'adopted')
                        <div class="text-center text-red-500">
                            <p>This pet has already been adopted.</p>
                        </div>
                    @endif
                    <!-- Adoption Status: Change ends here -->

                    <!-- Buttons -->
                    <div class="flex justify-between mt-4">
                        {{-- <button onclick="closeModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded cursor-pointer">
                            Close
                        </button> --}}

                        @if (auth()->user()->can('edit pet listing'))
                            <a href="{{ route('pets.edit', $pet->id) }}" class="
                                bg-primary text-white px-4 py-2 rounded hover:bg-white border-1 border-primary hover:text-primary transition cursor-pointer">
                                Edit Pet Listing
                            </a>
                        @endif

                        <!-- Delete Button (only visible to Shelter/Administrator) -->
                        @if (auth()->user()->can('delete pet listing'))
                            <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition cursor-pointer">
                                    Delete Pet Listing
                                </button>
                            </form>
                        @endif

                        <!-- Adopt Now Button: Change starts here -->
                        @if (auth()->guest() || auth()->user()->hasRole('Adopter'))
                            @if ($pet->status === 'adopted')
                                <button class="bg-gray-500 text-white px-4 py-2 rounded cursor-not-allowed" disabled>Adopt
                                    Now
                                </button>
                            @else
                                <a id="adoptNowBtn" href="{{ route('adopt.store', ['pet_id' => $pet->id]) }}"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Adopt
                                    Now
                                </a>
                            @endif
                        @endif
                        <!-- Adopt Now Button: Change ends here -->
                    </div>
                </div>
            </div>

        </div>


        <!-- JavaScript to Handle Modal -->
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
