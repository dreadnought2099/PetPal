@extends('layouts.app')

@section('content')
    <div class="min-h-[70vh] flex flex-col items-center text-center justify-center space-y-12 px-6 md:px-16">
        <h1 class="text-4xl md:text-5xl font-bold text-dark tracking-wide leading-tight">
            Connect with <span class="text-primary">PetPal</span>
        </h1>
        
        <!-- Social Media Links Container -->
        <div class="flex flex-wrap justify-center gap-6 text-lg">
            
            <!-- Email Link with Proper Alignment -->
            <a href="mailto:marjunmmagallanes@gmail.com" target="_blank"
                class="flex items-center space-x-2 text-gray-700 hover:text-primary hover:scale-105 transition-all duration-300">
                <img src="icon/envelope.svg" class="w-6 h-6" alt="Email">
                <span class="hover-underline-hyperlink">Email</span>
            </a>

            <!-- Facebook Link with Icon -->
            <a href="https://www.facebook.com/RMAGALLANEZ.18" target="_blank"
                class="flex items-center space-x-2 text-gray-700 hover:text-primary hover:scale-105 transition-all duration-300">
                <img src="icon/facebook.svg" class="w-6 h-6" alt="Facebook"> <!-- Increased icon size for better alignment -->
                <span class="hover-underline-hyperlink">Facebook</span> <!-- Added span for alignment consistency -->
            </a>

            <!-- Instagram Link with Icon -->
            <a href="https://www.instagram.com/skerm_art/" target="_blank"
                class="flex items-center space-x-2 text-gray-700 hover:text-primary hover:scale-105 transition-all duration-300">
                <img src="icon/instagram.svg" class="w-6 h-6" alt="Instagram"> <!-- Increased icon size for better alignment -->
                <span class="hover-underline-hyperlink">Instagram</span> <!-- Added span for consistency -->
            </a>

            <a href="https://www.youtube.com/@paradoxx_the_art" target="_blank"
                class="flex items-center space-x-2 text-gray-700 hover:text-primary hover:scale-105 transition-all duration-300">
                <img src="icon/youtube.svg" class="w-6 h-6" alt="Youtube"> <!-- Increased icon size for better alignment -->
                <span class="hover-underline-hyperlink">Youtube</span> <!-- Added span for consistency -->
            </a>
        </div>
    </div>
@endsection
