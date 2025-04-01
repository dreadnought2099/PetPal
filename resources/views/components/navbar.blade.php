<nav class="bg-primary flex justify-between items-center px-8 py-4 text-white">
    <div>
        <a href="{{ url('/') }}" class="flex flex-col text-3xl text-white hover-underline">
            PetPal <span class="text-sm">Find.Love.Adopt</span>
        </a>
    </div>

    <div class="flex items-center space-x-6 text-lg font-bold">
        <a href="{{ url('/') }}" class="hover-underline">Home</a>
        <a href="{{ route('pets.index') }}" class="hover-underline">Our Pets</a>
        @if (auth()->check() && auth()->user()->hasRole('Shelter|Administrator'))
            <a href="{{ route('pets.create') }}" class="hover-underline">Add Pet</a>
            <a href="{{ route('adopt.pending') }}" class="hover-underline">Pending Requests</a>
        @endif

        @auth
            @if(auth()->user()->hasRole('Adopter'))
                <a href="{{ route('adopt.index') }}" class="hover-underline">Apply for Adoption</a>
            @endif

            <div x-data="{ open: false }" class="relative">
                <!-- Dropdown Button -->
                <button @click="open = !open"
                    class="flex items-center space-x-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                    <span class="text-gray-700">{{ Auth::user()->name ?? 'Guest' }}</span>
                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute right-0 mt-2 w-44 bg-white border border-gray-300 shadow-lg rounded-lg z-10">
                    <ul class="py-2">
                        <li>
                            <a href="{{ route('profile.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile
                            </a>
                        </li>
                        @if (auth()->check() && auth()->user()->hasRole('Adopter'))
                            <li>
                                <a href="{{ route('adopt.log') }}" 
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Adoption Log
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-primary hover:bg-gray-100 cursor-pointer">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="hover-underline">Login</a>
            <a href="{{ route('register') }}" class="hover-underline">Register</a>
        @endauth
    </div>
</nav>
