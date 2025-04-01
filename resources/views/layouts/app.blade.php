<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('/icon/pawprint.png') }}">
    <title>PetPal</title>
    {{-- <style>
        #petModal {
            background-color: black;
            opacity: 80%;
            transition: background-color 0.3s ease;
        }

        #petModal.open {
            background-color: black;
            opacity: 80%;
            transition: background-color 0.3s ease;
        }
    </style> --}}
</head>

<body class="overflow-hidden">
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
        display: none;
    }
</style>
