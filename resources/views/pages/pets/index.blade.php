@extends('layouts.app')

@section('content')
    <div class="text-center mt-4">
        <h2 class="text-3xl font-bold text-dark">Our <span class="text-primary">Pets</span></h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8 cursor-pointer">
        @foreach ($pets as $pet)
            @include('components.pet-card', ['pet' => $pet])
        @endforeach
    </div>
@endsection
