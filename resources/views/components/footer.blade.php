<footer class="bg-green-100 shadow-inner mt-12">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <a href="{{ route('home') }}"
                    class="text-3xl font-bold text-primary hover-underline-hyperlink hover:scale-110 transition-all duration-300 ease-in-out">PetPal</a>
                <p class="mt-2 text-sm text-gray-600">Find. Love. Adopt.</p>
            </div>

            <div>
                <h3 class="text-md font-semibold text-gray-700 mb-3">Discover</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}"
                            class="text-sm text-primary hover-underline-hyperlink transition">About</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="text-sm text-primary hover-underline-hyperlink transition">Contact</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-md font-semibold text-gray-700 mb-3">Connect</h3>
                <ul class="space-y-2">
                    <li><a href="https://www.facebook.com/RMAGALLANEZ.18" target="_blank"
                            class="flex items-center space-x-2 text-gray-700 hover:text-primary hover:scale-105 transition-all duration-300">
                            <img src="/icon/facebook.svg" class="w-6 h-6" alt="Facebook">
                            <span class="hover-underline-hyperlink">Facebook</span>
                        </a>
                    </li>

                    <li><a href="https://www.instagram.com/skerm_art/" target="_blank"
                            class="flex items-center space-x-2 text-gray-700 hover:text-primary hover:scale-105 transition-all duration-300">
                            <img src="/icon/instagram.svg" class="w-6 h-6" alt="Instagram">
                            <span class="hover-underline-hyperlink">Instagram</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-200 mt-10 pt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-500">
                &copy; <span id="year"></span> <span class="hover-underline-hyperlink cursor-pointer"> PetPal.</span> All Rights Reserved.
            </a>
        </div>
    </div>
</footer>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();
</script>
