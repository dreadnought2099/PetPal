@extends('layouts.app')

@section('content')
    <div class="text-center mt-4 relative w-full">


        <h2 class="text-3xl font-bold text-dark">Our <span class="text-primary">Pets</span></h2>
    </div>


    <script>
        let selectedId = "";
    </script>
    <div class="flex flex-wrap gap-2 p-6 h-[70vh] overflow-y-auto select-none">
        @forelse ($pets as $pet)
            <div @if ($pet->status != 'adopted') onclick='showPetModal({{ $pet->id }})' @endif
                class="hover:scale-90 transition-transform ease-in-out duration-300
                basis-[249px] bg-white p-4 rounded-lg shadow-md relative border-1
            {{ $pet->status === 'adopted'
                ? 'border-secondary opacity-75 cursor-not-allowed pointer-events-none '
                : 'hover:shadow-lg border-primary transition-shadow duration-200 cursor-pointer' }}">

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
                    <button onclick='showPetModal({{ $pet->id }})'
                        class="mt-4 text-primary hover:underline cursor-pointer">
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


    <script>
        function showPetModal(id) {
            if (this.selectedId === id) {
                this.selectedId = "";
                document.getElementById(`modal-${id}`).style.display = 'none';
            } else {
                this.selectedId = id;
                document.getElementById(`modal-${id}`).style.display = 'flex ';
            }

        }
    </script>

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
        <div id="modal-{{ $pet->id }}" class="modal">
            <div class="bg-white flex relative flex-col rounded-xl w-100">
                <button onclick="showPetModal({{ $pet->id }})" title="Click to close the modal"
                    class="flex absolute right-3 text-red-500 text-4xl hover:text-gray-200 hover:cursor-pointer">&times;</button>
                <img src="/storage/{{ $pet->pet_profile_path }}" alt="Pet Image"
                    class="w-full h-48 object-cover rounded-t-xl mb-4">
                <h2 id="petName" class="text-2xl font-bold text-center mb-4"></h2>
                <div class="p-2 grid grid-cols-2 gap-4">
                    <p>
                        <strong>Breed:</strong>
                        <span>
                            {{ $pet->breed }}
                        </span>
                    </p>
                    <p>

                        <strong>Age:</strong>
                        <span>
                            {{ $pet->age }}

                        </span>
                        years
                    </p>
                    <p>
                        <strong>Sex:</strong>
                        <span>
                            {{ $pet->sex }}
                        </span>
                    </p>
                    <p>
                        <strong>Species:</strong>
                        <span>
                            {{ $pet->species === 0 ? 'Cat' : 'Dog' }}
                        </span>
                    </p>
                    <p>
                        <strong>Allergies:</strong>
                        <span>
                            {{ $pet->allergies }}
                        </span>
                    </p>
                    <p>
                        <strong>Vaccination:</strong>
                        <span>
                            @if ($pet->vaccination === 0)
                                None
                            @elseif($pet->vaccination === 1)
                                Partially
                            @elseif($pet->vaccination === 2)
                                Fully
                            @endif
                        </span>
                    </p>
                    <p>
                        <strong>Spayed/Neutered:</strong>
                        <span>
                            {{ $pet->spayed_neutered === 1 ? 'Yes' : 'No' }}
                        </span>
                    </p>


                </div>
                <div class="p-2">
                    <p class="mt-3"><strong>Description:</strong></p>
                    <p class="border p-2 rounded bg-gray-50">{{ $pet->description }}</p>
                </div>

                <div class="flex justify-between p-2 w-full">

                    @can('edit pet listing')
                        <a href="{{ route('pets.edit', $pet->id) }}"
                            class="bg-primary text-white px-4 py-2 rounded hover:bg-white hover:text-primary border border-primary transition-colors">
                            Edit
                        </a>
                    @endcan

                    @can('delete pet listing')
                        <a href="{{ route('pets.destroy', $pet->id) }}"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">
                            Delete
                        </a>
                    @endcan

                </div>
                <p id="alreadyAdoptedMsg" class=" text-red-500 mt-2 text-sm"></p>

                <div class="flex p-2 w-full">

                    @hasrole('Adopter')
                        <a href="{{ route('adopt.apply', $pet->id) }}"
                            class="w-full px-4 py-2 bg-primary text-white text-sm font-semibold rounded-md
                    hover:bg-white hover:text-primary border border-transparent hover:border-primary transition duration-300 ease-in-out">
                            Adopt Now
                        </a>
                    @endhasrole
                </div>


            </div>
        </div>
    @endforeach
@endsection
