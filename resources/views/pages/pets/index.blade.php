@extends('layouts.app')

@section('content')
    <div class="text-center mt-4 relative w-full">
        <h2 class="text-3xl font-bold text-dark">Our <span class="text-primary">Pets</span></h2>
    </div>

    <div class="flex flex-wrap gap-2 p-6 h-[70vh] overflow-y-auto select-none">
        @forelse ($pets as $pet)
            <div @if ($pet->status !== 'adopted') onclick="showPetModal({{ $pet->id }})" @endif
                @class([
                    'hover:scale-90 transition-transform ease-in-out duration-300',
                    'basis-[249px] bg-white p-4 rounded-lg shadow-md relative border-1',
                    'cursor-not-allowed pointer-events-none border-secondary opacity-75' =>
                        $pet->status === 'adopted',
                    'hover:shadow-lg border-primary transition-shadow duration-200 cursor-pointer' =>
                        $pet->status !== 'adopted',
                ])>
                @if ($pet->status === 'adopted')
                    <span class="absolute top-2 right-2 bg-note text-white text-xs px-2 py-1 rounded-full z-20">
                        Adopted
                    </span>
                @endif

                <img src="{{ $pet->pet_profile_path ? Storage::url($pet->pet_profile_path) : asset('images/LRM_20240517_192913-01.jpeg') }}"
                    alt="{{ $pet->name }}"
                    class="w-full h-48 object-cover rounded-lg @if ($pet->status === 'adopted') grayscale @endif">

                <h3 class="text-xl font-bold mt-4">{{ $pet->name }}</h3>
                <p class="text-gray-600">{{ $pet->breed }}</p>

                <p
                    class="text-sm mt-2 font-medium
                    {{ match ($pet->status) {
                        'adopted' => 'text-note',
                        'pending' => 'text-yellow-500',
                        default => 'text-primary',
                    } }}">
                    <span class="text-black">Status:</span> {{ ucfirst($pet->status ?? 'available') }}
                </p>

                @if ($pet->status !== 'adopted')
                    <button onclick="event.stopPropagation(); showPetModal({{ $pet->id }})"
                        class="mt-4 text-primary hover-underline-hyperlink cursor-pointer">
                        See more
                    </button>
                @else
                    <p class="mt-4 text-note text-sm">{{ $pet->name }} has been adopted</p>
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

    <style>
        .modal {
            display: none;
            position: fixed;
            align-items: center;
            justify-content: center;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 100;
        }
    </style>

    @foreach ($pets as $pet)
        <div id="modal-{{ $pet->id }}" class="modal" onclick="outsideClick(event, {{ $pet->id }})">
            <div class="bg-white flex relative flex-col
            rounded-xl w-100">
                <button onclick="showPetModal({{ $pet->id }})" title="Click to close the modal"
                    class="flex absolute right-3 text-red-500 text-4xl hover:text-gray-200 hover:cursor-pointer">&times;</button>

                <img src="{{ $pet->pet_profile_path ? Storage::url($pet->pet_profile_path) : asset('images/LRM_20240517_192913-01.jpeg') }}"
                    alt="{{ $pet->name }}" class="w-full h-48 object-cover rounded-t-xl mb-4">

                <h2 class="text-2xl font-bold text-center mb-4"><span class="text-primary">{{ $pet->name }}</span></h2>

                <div class="p-2 grid grid-cols-2 gap-4 text-sm">
                    <p><strong>Breed:</strong> {{ $pet->breed }}</p>
                    <p><strong>Age:</strong> {{ $pet->age }} years</p>
                    <p><strong>Sex:</strong> {{ $pet->sex }}</p>
                    <p><strong>Species:</strong> {{ $pet->getSpeciesTextAttribute() }}</p>
                    <p><strong>Allergies:</strong> {{ $pet->allergies }}</p>
                    <p><strong>Vaccination: {{ $pet->getVaccinationTextAttribute() }}</p>
                    <p><strong>Spayed/Neutered:</strong> {{ $pet->spayed_neutered ? 'Yes' : 'No' }}</p>
                </div>

                <div class="p-2">
                    <p class="mt-3 font-semibold">Description:</p>
                    <p class="border p-2 rounded bg-gray-50 text-sm break-words max-h-40 overflow-auto">
                        {{ $pet->description }}
                    </p>
                </div>

                <div class="flex justify-between p-2 w-full">
                    @can('edit pet listing')
                        <a href="{{ route('pets.edit', $pet->id) }}"
                            class="bg-primary text-white px-4 py-2 rounded hover:bg-white hover:text-primary border border-primary transition-colors">
                            Edit
                        </a>
                    @endcan

                    <form action="{{ route('pets.destroy', $pet->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete pet {{ $pet->name }}?');">
                        @csrf
                        @method('DELETE')
                        @can('delete pet listing')
                            <button type="submit"
                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-white border hover:border-secondary hover:text-secondary transition-colors cursor-pointer">
                                Delete
                            </button>
                        @endcan

                    </form>
                </div>

                <div class="flex p-2 w-full">
                    @if (auth()->guest() || auth()->user()->hasRole('Adopter'))
                        <a href="{{ route('adopt.apply', $pet->id) }}"
                            class="w-full px-4 py-2 bg-primary text-white text-sm font-semibold rounded-md
                                hover:bg-white hover:text-primary border hover:border-primary transition duration-300">
                            Adopt Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Script Loaded!');
            let selectedId = "";

            window.showPetModal = function(id) {
                console.log(`showPetModal called with id: ${id}`)
                const prevModal = selectedId ? document.getElementById(`modal-${selectedId}`) : null;
                const currentModal = document.getElementById(`modal-${id}`);

                if (selectedId === id.toString()) {
                    selectedId = "";
                    currentModal.style.display = 'none';
                } else {
                    if (prevModal) prevModal.style.display = 'none';
                    selectedId = id.toString();
                    currentModal.style.display = 'flex';
                }
            };

            window.outsideClick = function(event, id) {
                const modal = document.getElementById(`modal-${id}`);
                if (event.target === modal) {
                    showPetModal(id);
                }
            }
        })
    </script>
@endsection
