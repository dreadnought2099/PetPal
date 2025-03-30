@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg border border-primary">
            <h2 class="text-2xl font-bold text-center mb-4">
                Profil<span class="text-primary">e</span>
            </h2>
            <div class="space-y-3">
                <p><strong>Name: </strong>{{ $user->name }}</p>
                <p><strong>Email: </strong>{{ $user->email }}</p>
                <p><strong>Joined: </strong>{{ $user->created_at->format('F d, Y') }}</p>
            </div>
        </div>
    </div>
@endsection
