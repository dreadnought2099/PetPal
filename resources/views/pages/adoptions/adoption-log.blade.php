@extends('layouts.app')

@section('content')
    <div
        class="container mx-auto max-w-5xl bg-white mt-6 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] p-6">
        <div class="relative w-full">

            {{-- Session Message --}}
            <div id="success-message-container" class="absolute top-4 left-1/2 transform -translate-x-1/2 z-20">
                @if (session('success') || session('info'))
                    <div id="message"
                        class="p-4 rounded-md shadow-lg border-l-4
                        {{ session('success') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} 
                        transform opacity-0 transition-all duration-500 ease-in-out">
                        <span>{{ session('success') ?? session('info') }}</span>
                    </div>

                    <script>
                        setTimeout(() => {
                            let messageDiv = document.getElementById('message');
                            if (messageDiv) {
                                messageDiv.classList.add('opacity-0'); // Start fade out
                                setTimeout(() => {
                                    messageDiv.style.display = 'none'; // Remove after fade-out
                                }, 500);
                            }
                        }, 4000);
                    </script>
                @endif
            </div>

            <h2 class="flex gap-[5px] sticky top-0 py-3 px-6 text-2xl font-semibold bg-white z-10 justify-center">
                Adoption <span class="text-primary">Log</span>
            </h2>

            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-3 text-left text-sm font-semibold">ID</th>
                            <th class="border p-3 text-left text-sm font-semibold">Adopter</th>
                            <th class="border p-3 text-left text-sm font-semibold">Pet ID</th>
                            <th class="border p-3 text-left text-sm font-semibold">Status</th>
                            <th class="border p-3 text-center text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($adoptions as $adoption)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border p-3 text-sm">{{ $adoption->id }}</td>
                                <td class="border p-3 text-sm">{{ $adoption->first_name }} {{ $adoption->last_name }}</td>
                                <td class="border p-3 text-sm">{{ $adoption->pet_id }}</td>
                                <td
                                    class="border p-3 text-sm
                                    {{ $adoption->status === 'approved'
                                        ? 'text-green-500'
                                        : ($adoption->status === 'rejected'
                                            ? 'text-red-500'
                                            : 'text-yellow-400') }}">
                                    {{ ucfirst($adoption->status) }}
                                </td>
                                <td class="border p-3 text-center">
                                    @if (Auth::id() === $adoption->user_id && $adoption->status === 'pending')
                                        <a href="{{ route('adopt.edit', $adoption->id) }}"
                                            class="bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary-dark transition duration-300 ease-in-out mb-2 inline-block">
                                            Edit
                                        </a>
                                        <form action="{{ route('adopt.destroy', $adoption->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition duration-300 ease-in-out inline-block">
                                                Delete
                                            </button>
                                        </form>
                                    @elseif(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Shelter'))
                                        <form action="{{ route('adopt.approve', $adoption->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition duration-300 ease-in-out mb-2 inline-block">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('adopt.reject', $adoption->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition duration-300 ease-in-out inline-block">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">No actions available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
