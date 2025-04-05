<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('/icon/pawprint.png') }}">

    <title>PetPal</title>
</head>

<body class="overflow-hidden">
    {{-- Session Message --}}
    <div id="success-message-container" class="absolute top-24 right-4 z-10">
        @if (session('success') || session('error'))
            <div id="message"
                class="p-3 rounded-md shadow-lg border-l-4
          {{ session('success') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ session('success') ?? session('error') }}
            </div>

            <script>
                setTimeout(() => {
                    let messageDiv = document.getElementById('message');
                    if (messageDiv) {
                        messageDiv.style.display = 'none';
                    }
                }, 4000);
            </script>
        @endif
    </div>


    @include('components.navbar')

    <div class="container">
        @yield('content')
    </div>

    {{-- Alpine.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>


</html>

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
