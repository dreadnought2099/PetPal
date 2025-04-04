@extends('layouts.app')

@section('content')
    <div class="text-center mt-4 relative w-full">
        {{-- Session Message --}}
        @if (session('success') || session('info'))
            <div id="message"
                class="absolute top-4 right-4 z-50 p-3 rounded-md shadow-lg border-l-4 {{ session('success') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ session('success') ?? session('info') }}
            </div>
            <script>
                setTimeout(() => document.getElementById('message')?.classList.add('hidden'), 4000);
            </script>
        @endif

        <h2 class="text-3xl font-bold text-dark">Our <span class="text-primary">Pets</span></h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8">
        @forelse ($pets as $pet)
            <div
                class="bg-white p-4 rounded-lg shadow-md relative border-1 border-primary
                @if ($pet->status === 'adopted') opacity-75 cursor-not-allowed
                @else
                    hover:shadow-lg transition-shadow duration-200 @endif">

                @if ($pet->status === 'adopted')
                    {{-- <div class="absolute inset-0 bg-white bg-opacity-70 z-10"></div> --}}
                    <span class="absolute top-2 right-2 bg-note text-white text-xs px-2 py-1 rounded-full z-20">
                        Adopted
                    </span>
                @endif

                <img src="{{ $pet->pet_profile_path ? Storage::url($pet->pet_profile_path) : asset('images/LRM_20240517_192913-01.jpeg') }}"
                    alt="{{ $pet->name }}"
                    class="w-full h-48 object-cover rounded-lg @if ($pet->status === 'adopted') grayscale @endif">

                <h3 class="text-xl font-bold mt-4">{{ $pet->name }}</h3>
                <p class="text-gray-600">{{ $pet->breed }}</p>
                @php
                    $statusColor = match ($pet->status) {
                        'adopted' => 'text-note',
                        'pending' => 'text-yellow-500',
                        default => 'text-primary',
                    };
                @endphp

                <p class="text-sm mt-2 font-medium {{ $statusColor }}">
                    <span class="text-black">Status:</span> {{ ucfirst($pet->status ?? 'available') }}
                </p>


                @if ($pet->status !== 'adopted')
                    <button onclick='openModal(@json($pet))' class="mt-4 text-primary hover:underline">
                        See more
                    </button>
                @else
                    <p class="mt-4 text-note text-sm">This pet has been adopted</p>
                @endif
            </div>
        @empty
            <div class="col-span-4 text-center py-8">
                <p class="text-lg text-gray-600">No pets available for adoption at this time.</p>
                @auth
                    @can('create pet listing')
                        <a href="{{ route('pets.create') }}"
                            class="mt-4 inline-block bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition-colors">
                            Add a Pet
                        </a>
                    @endcan
                @endauth
            </div>
        @endforelse
    </div>

    <!-- Modal -->
    <div id="petModalOverlay" class="fixed inset-0 bg-black bg-opacity-10 hidden z-40">
        <div id="petModal" class="flex fixed inset-0 items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg relative mx-4">
                <button onclick="closeModal()"
                    class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>
                <img id="petImage" src="" alt="Pet Image" class="w-full h-48 object-cover rounded-lg mb-4">
                <h2 id="petName" class="text-2xl font-bold text-center mb-4"></h2>
                <div class="grid grid-cols-2 gap-4">
                    <p><strong>Breed:</strong> <span id="petBreed"></span></p>
                    <p><strong>Age:</strong> <span id="petAge"></span> years</p>
                    <p><strong>Sex:</strong> <span id="petSex"></span></p>
                    <p><strong>Species:</strong> <span id="petSpecies"></span></p>
                    <p><strong>Allergies:</strong> <span id="petAllergies"></span></p>
                    <p><strong>Vaccination:</strong> <span id="petVaccination"></span></p>
                    <p><strong>Spayed/Neutered:</strong> <span id="petSpayedNeutered"></span></p>
                </div>
                <p class="mt-3"><strong>Description:</strong></p>
                <p id="petDescription" class="border p-2 rounded bg-gray-50"></p>

                <div class="flex justify-between items-center mt-4 flex-wrap gap-2">
                    @auth
                        @can('edit pet listing')
                            <a id="editPetLink"
                                class="hidden bg-primary text-white px-4 py-2 rounded hover:bg-white hover:text-primary border border-primary transition-colors">
                                Edit
                            </a>
                        @endcan

                        @can('delete pet listing')
                            <form id="deletePetForm" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    @endauth
                    <p id="alreadyAdoptedMsg" class="hidden text-red-500 mt-2 text-sm"></p>
                </div>
                @auth
                    @if (auth()->user()->hasRole('Adopter'))
                        <a id="adoptNowBtn"
                            class="hidden mt-2 w-full bg-primary text-white text-sm font-semibold py-2 px-4 rounded-md 
                        hover:bg-white hover:text-primary border border-transparent hover:border-primary transition duration-300 ease-in-out">
                            Adopt Now
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <script>
        function openModal(pet) {
            if (pet.status === 'adopted') return;

            const {
                name,
                breed,
                age,
                sex,
                species,
                allergies,
                vaccination,
                spayed_neutered,
                description,
                pet_profile_path,
                id,
                status
            } = pet;

            document.getElementById('petName').innerText = name;
            document.getElementById('petBreed').innerText = breed;
            document.getElementById('petAge').innerText = age;
            document.getElementById('petSex').innerText = sex === 'M' ? 'Male' : 'Female';
            document.getElementById('petSpecies').innerText = species == 0 ? 'Dog' : 'Cat';
            document.getElementById('petAllergies').innerText = allergies || 'None';

            const vaccinationStatus = {
                0: 'Not Vaccinated',
                1: 'Partially Vaccinated',
                3: 'Fully Vaccinated'
            };
            document.getElementById('petVaccination').innerText = vaccinationStatus[vaccination] || 'Unknown';

            document.getElementById('petSpayedNeutered').innerText = spayed_neutered ? 'Yes' : 'No';
            document.getElementById('petDescription').innerText = description || 'No description available';
            document.getElementById('petImage').src = pet_profile_path ? `/storage/${pet_profile_path}` :
                '/images/default-pet.jpg';

            // Show/hide buttons
            const adoptBtn = document.getElementById('adoptNowBtn');
            const alreadyMsg = document.getElementById('alreadyAdoptedMsg');
            if (adoptBtn && alreadyMsg) {
                const canAdopt = status === 'available' || status === 'pending';
                if (canAdopt) {
                    adoptBtn.classList.remove('hidden');
                    alreadyMsg.classList.add('hidden');
                    adoptBtn.setAttribute('href', `/adoption/apply?pet_id=${id}`);
                } else {
                    adoptBtn.classList.add('hidden');
                    alreadyMsg.classList.remove('hidden');
                    alreadyMsg.innerText = `${name} has already been adopted.`;
                }
            }

            const editLink = document.getElementById('editPetLink');
            const deleteForm = document.getElementById('deletePetForm');

            if (editLink) {
                if (status !== 'adopted') {
                    editLink.href = `/pets/${id}/edit`;
                    editLink.classList.remove('hidden');
                } else {
                    editLink.classList.add('hidden');
                }
            }

            if (deleteForm) {
                if (status !== 'adopted') {
                    deleteForm.action = `/pets/${id}`;
                    deleteForm.classList.remove('hidden');
                } else {
                    deleteForm.classList.add('hidden');
                }
            }

            document.getElementById('petModalOverlay').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('petModalOverlay').classList.add('hidden');
        }
    </script>
@endsection
