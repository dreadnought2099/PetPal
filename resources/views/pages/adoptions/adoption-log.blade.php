@extends('layouts.app')

@section('content')
    <div
        class="container mx-auto max-w-5xl bg-white mt-6 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] p-6">
        <div class="relative w-full">
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
                                            class="bg-primary text-white py-2 px-4 mx-4 rounded-md hover:bg-white hover:text-primary border-1 border-primary transition-all ease-in-out duration-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('adopt.destroy', $adoption->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-white hover:text-red-500 border-1 border-red-500 transition-all duration-300 ease-in-out">
                                                Delete
                                            </button>
                                        </form
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
