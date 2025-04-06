@extends('layouts.app') <!-- Assuming you're using a master layout -->

@section('content')
<div class="container mx-auto mt-4">
    <h1 class="text-2xl font-bold mb-6">Pending Adoption Requests</h1>

    <!-- Table to display adoption requests -->
    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr>
                <th class="px-4 py-2 border-b">Adopter Name</th>
                <th class="px-4 py-2 border-b">Pet</th>
                <th class="px-4 py-2 border-b">Contact</th>
                <th class="px-4 py-2 border-b">Status</th>
                <th class="px-4 py-2 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adoptions as $adoption)
                @if($adoption->status === 'pending')
                    <tr>
                        <td class="px-4 py-2 border-b">{{ $adoption->first_name }} {{ $adoption->last_name }}</td>
                        <td class="px-4 py-2 border-b">{{ $adoption->pet->name }} ({{ $adoption->pet->species }})</td>
                        <td class="px-4 py-2 border-b">{{ $adoption->contact_number }}</td>
                        <td class="px-4 py-2 border-b">{{ ucfirst($adoption->status) }}</td>
                        <td class="px-4 py-2 border-b">
                            <form action="{{ route('adopt.approve', $adoption) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Approve</button>
                            </form>

                            <form action="{{ route('adopt.reject', $adoption) }}" method="POST" class="inline-block ml-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 border-b text-center">No pending adoption requests</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
