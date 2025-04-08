@extends('layouts.app') <!-- Assuming you're using a master layout -->

@section('content')
    <div
        class="container mx-auto max-w-4xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-auto h-[80vh] space-y-10">
        <h1 class="flex gap-1 sticky top-0 py-3 px-4 text-xl font-bold bg-white z-10 justify-center">Pending Adoption <span
                class="text-primary">Requests</span>
        </h1>
        <p class="text-gray-500 flex gap-1 sticky top-0 px-2 z-10">Below are the pending requests for pet adoption</p>
        <!-- Table -->
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 border-b text-left">Adopter Name</th>
                    <th class="px-6 py-3 border-b text-left">Pet</th>
                    <th class="px-6 py-3 border-b text-left">Contact</th>
                    <th class="px-6 py-3 border-b text-left">Status</th>
                    <th class="px-6 py-3 border-b text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adoptions as $adoption)
                    @if ($adoption->status === 'pending')
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 border-b">{{ $adoption->first_name }} {{ $adoption->last_name }}</td>
                            <td class="px-6 py-3 border-b">{{ $adoption->pet->name }} ({{ $adoption->pet->species }})</td>
                            <td class="px-6 py-3 border-b">{{ $adoption->contact_number }}</td>
                            <td class="px-6 py-3 border-b 
                                    {{ $adoption->status === 'approved'
                                        ? 'text-primary'
                                    : ($adoption->status === 'pending'
                                        ? 'text-yellow-500'
                                    : ($adoption->status === 'rejected'
                                            ? 'text-red-500' : '')) }}">
                                {{ ucfirst($adoption->status) }}
                            </td>
                            <td class="px-6 py-3 border-b">
                                <div class="flex gap-2">
                                    <form action="{{ route('adopt.approve', $adoption) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-green-500 text-white px-3 py-1.5 rounded hover:bg-green-700">Approve</button>
                                    </form>

                                    <form action="{{ route('adopt.reject', $adoption) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1.5 rounded hover:bg-red-700">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-3 border-b text-center">No pending adoption requests</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
