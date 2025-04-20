<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>PetPal</title>

    <link rel="icon" href="{{ asset('icon/pawprint.png') }}" type="image/png">
    @vite('resources/css/app.css')

    <style>
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-white">
    <div id="success-message-container" class="absolute top-24 right-4 z-10">
        @if (session('success') || session('error') || session('info') || $errors->any())
            <div id="message"
                class="p-3 rounded-md shadow-lg border-l-4
                {{ session('success') ? 'bg-green-100 text-green-700' : '' }}
                {{ session('error') ? 'bg-red-100 text-red-700' : '' }}
                {{ session('info') ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $errors->any() ? 'bg-red-100 text-red-700' : '' }}">

                {{-- Display session messages --}}
                @if (session('success'))
                    <p>{{ session('success') }}</p>
                @endif
                @if (session('error'))
                    <p>{{ session('error') }}</p>
                @endif
                @if (session('info'))
                    <p>{{ session('info') }}</p>
                @endif

                {{-- Display validation errors --}}
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>

            <script>
                setTimeout(() => {
                    const messageDiv = document.getElementById('message');
                    if (messageDiv) {
                        messageDiv.classList.add('opacity-0');
                        setTimeout(() => {
                            messageDiv.style.display = 'none';
                        }, 500);
                    }
                }, 5000);
            </script>
        @endif
    </div>

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main class="flex-grow container mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>

</html>
