<div class="bg-green-100 border border-green-500 p-4 rounded-lg text-center">
    <img src="{{ $pet->image_url ?? asset('default-pet.jpg') }}" alt="{{ $pet->name }}" class="w-32 h-32 mx-auto rounded-lg">
    <p class="mt-2 font-bold text-green-600">{{ strtoupper($pet->name) }}</p>
</div>
