<div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center transition-all hover:shadow-md hover:border-green-300">
    <!-- Optimized image with lazy loading and better error handling -->
    <img 
        src="{{ $pet->pet_profile_path ? asset('storage/' . $pet->pet_profile_path) : asset('images/LRM_20240517_192913-01.jpeg') }}" 
        alt="{{ $pet->name }} photo"
        class="w-32 h-32 mx-auto rounded-lg object-cover shadow-sm"
        loading="lazy"
        onerror="this.src='{{ asset('images/LRM_20240517_192913-01.jpeg') }}'"
    >

    <!-- Pet Name -->
    <h3 class="mt-3 font-semibold text-green-700 text-lg">
        <span class="sr-only">Pet name: </span>
        {{ ucfirst($pet->name) }}
    </h3>

    <!-- Pet Details -->
    <div class="mt-1 text-sm text-green-600">
        {{ $pet->breed }} • {{ $pet->age }} years
    </div>

    <!-- View Details Button -->
    <button 
        onclick="openModal({{ json_encode($pet) }})" 
        class="bg-green-500 text-white px-4 py-2 rounded mt-3 w-full hover:bg-green-600 transition">
        View Details
    </button>
</div>
