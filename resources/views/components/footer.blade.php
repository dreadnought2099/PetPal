<footer class="bg-green-100 shadow-sm mt-8">
    <div class="container mx-auto px-4 py-6 md:py-8">
        <div class="text-center">
            <a href="{{ route('home') }}" class="flex flex-col text-2xl font-semibold text-primary hover-underline-hyperlink ">
                PetPal <span class="text-sm">Find.Love.Adopt</span>
            </a>
        </div>

        <!-- Navigation Section -->
        <nav class="flex justify-center space-x-6 text-sm font-medium text-primary mt-4 my-6">
            <a href="{{ route('about') }}" class="hover-underline-hyperlink">About</a>
            <a href="{{ route('contact') }}" class="hover-underline-hyperlink">Contact</a>
        </nav>

        <!-- Copyright Section -->
        <div class="text-center">
            <p class="text-sm text-gray-500">
                &copy; <span id="year"></span>
                <a href="{{ route('home') }}" class="hover-underline-hyperlink">PetPal</a>. All Rights Reserved.
            </p>
        </div>
    </div>
</footer>

<script>
    // Set the current year dynamically in the footer
    document.getElementById('year').innerText = new Date().getFullYear();
</script>
