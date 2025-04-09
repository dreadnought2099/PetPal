@extends('layouts.app')

@section('content')
    <div
        class="container mx-auto max-w-4xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-auto h-[80vh] p-6 space-y-10">
        <h1 class="flex items-center justify-center text-xl font-bold bg-white sticky top-0 py-3 px-4 z-10">
            Pending Adoption <span class="text-primary ml-1">Requests</span>
        </h1>
        <p class="text-gray-400 sticky top-12 px-4 z-10">
            Below are the pending requests for pet adoption
        </p>

        @forelse($adoptions as $adoption)
            @if ($adoption->status === 'pending')
                <div class="flex items-center justify-between gap-4 px-4 py-4 border-b border-gray-200 w-full">
                    <p class="text-gray-700">
                        <span class="text-base text-primary font-medium">{{ $adoption->first_name }} {{ $adoption->last_name }}</span>
                        requested an adoption for
                        <span class="text-base text-primary font-medium">{{ $adoption->pet->name }}</span>
                    </p>
                    <button onclick="showAdoptionRequest({{ $adoption->id }})"
                        class="bg-primary text-white text-sm font-semibold rounded-md hover:bg-white hover:text-primary border hover:border-primary transition duration-300 px-4 py-2 cursor-pointer">
                        View Details
                    </button>
                </div>
            @endif
        @empty
            <div class="text-center py-6">
                <p colspan="5" class="px-6 py-3 border-b text-center">No pending adoption requests</p>
            </div>
        @endforelse
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal>div {
            max-width: 600px;
            width: 100%;
            padding: 16px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>

    @foreach ($adoptions as $adoption)
        <div id="modal-{{ $adoption->id }}" class="modal" onclick="outsideClick(event, {{ $adoption->id }})">
            <div class="flex flex-col relative border-1 shadow-md border-primary bg-white rounded-xl">
                <button onclick="showAdoptionRequest({{ $adoption->id }})" title="Click to close the modal"
                    class="absolute right-3 top-2 text-red-500 text-4xl hover:text-gray-400 hover:cursor-pointer">
                    &times;
                </button>

                <h2 class="text-xl mb-4 text-center py-6">Adoption Request <span class="text-primary">Details</span></h2>
                <p class="text-gray-700">Adopter's Name: <span class="text-primary">{{ $adoption->first_name }} {{ $adoption->last_name }}</span></p>
                <p class="text-gray-700">Pet Name: <span class="text-primary">{{ $adoption->pet->name }}</span></p>
                <p class="text-gray-700">Address: <span class="text-primary">{{ $adoption->address }}</span></p>
                <p class="text-gray-700">Contact Number: <span class="text-primary">{{ $adoption->contact_number }}</span></p>
                <p class="text-gray-700">Date of Birth: <span class="text-primary">{{ \Carbon\Carbon::parse($adoption->dob)->format('F d, Y') }}</span></p>
                <p class="text-gray-700">Previous Pet Experience: <span class="text-primary">{{ ucfirst($adoption->previous_experience) }}</span></p>
                <p class="text-gray-700">Do you have other pet at home: <span class="text-primary">{{ ucfirst($adoption->other_pets) }}</span></p>
                <p class="text-gray-700">Financial Preparedness: <span class="text-primary">{{ ucfirst($adoption->financial_preparedness) }}</span></p>
                <p class="text-gray-700">
                    Valid ID:
                    <a href="{{ asset('storage/' . $adoption->valid_id) }}" target="_blank"
                        class="text-primary hover-underline-hyperlink hover:scale-125 transition-all ease-in-out duration-300">View</a>
                </p>

                <div class="flex justify-end gap-4 mt-4">
                    <form action="{{ route('adopt.approve', $adoption->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="bg-primary text-white px-4 py-2 rounded hover:bg-white hover:text-primary transition-all ease-in-out duration-300 border-1 border-primary cursor-pointer">
                            Approve
                        </button>
                    </form>
                    <form action="{{ route('adopt.reject', $adoption->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-white hover:text-red-500 border-1 border-red-500 transition-all ease-in-out cursor-pointer">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Script Loaded!');
            let adoptionId = "";

            window.showAdoptionRequest = function(id) {
                console.log(`showAdoptionRequest called with id: ${id}`);
                const prevModal = adoptionId ? document.getElementById(`modal-${adoptionId}`) : null;
                const currentModal = document.getElementById(`modal-${id}`);

                if (!currentModal) {
                    console.error(`Modal with ID modal-${id} not found`);
                    return;
                }

                if (adoptionId === id.toString()) {
                    console.log("Hiding current modal");
                    adoptionId = "";
                    currentModal.style.display = 'none';
                } else {
                    console.log("Showing new modal");
                    if (prevModal) {
                        prevModal.style.display = 'none';
                    }
                    adoptionId = id.toString();
                    currentModal.style.display = 'flex';
                }
            };

            window.outsideClick = function(event, id) {
                const modal = document.getElementById(`modal-${id}`);
                if (event.target === modal) {
                    showAdoptionRequest(id);
                }
            };
        });
    </script>
@endsection
