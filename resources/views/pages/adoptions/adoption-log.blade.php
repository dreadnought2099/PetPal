@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Adoption Log</h2>

    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">ID</th>
                <th class="border p-2">Adopter</th>
                <th class="border p-2">Pet ID</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($adoptions as $adoption)
            <tr>
                <td class="border p-2">{{ $adoption->id }}</td>
                <td class="border p-2">{{ $adoption->first_name }} {{ $adoption->last_name }}</td>
                <td class="border p-2">{{ $adoption->pet_id }}</td>
                <td class="border p-2">{{ ucfirst($adoption->status) }}</td>
                <td class="border p-2">
                    @if (Auth::id() === $adoption->user_id && $adoption->status === 'pending')
                        <a href="{{ route('adopt.edit', $adoption->id) }}" class="border-1 hover:border-primary bg-primary hover:bg-white hover:text-primary text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out">
                            Edit
                        </a>
                        <form action="{{ route('adopt.destroy', $adoption->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="bg-gray-500 text-white px-2 py-1 rounded">Delete</button>
                        </form>
                    @elseif(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Shelter'))
                        <form action="{{ route('adopt.approve', $adoption->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="bg-green-500 text-white px-2 py-1 rounded">Approve</button>
                        </form>
                        <form action="{{ route('adopt.reject', $adoption->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="bg-red-500 text-white px-2 py-1 rounded">Reject</button>
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
@endsection
