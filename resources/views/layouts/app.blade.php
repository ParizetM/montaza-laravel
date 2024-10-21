<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @if (session('status'))
    <div id="flash-message" class="fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-green-500 text-white p-4 rounded shadow-lg z-50 transition-transform duration-500 ease-in-out">
        <div class="container mx-auto flex justify-between items-center">
            <span>{{ session('status') }}</span>
            <button onclick="hideFlashMessage()" class="text-white font-bold ml-3">X</button>
        </div>
    </div>

    <script>
        // Fonction pour afficher le message avec une transition de glissement
        function showFlashMessage() {
            const flashMessage = document.getElementById('flash-message');
            flashMessage.classList.remove('-translate-y-full'); // Enlève la classe pour montrer l'élément
            flashMessage.classList.add('translate-y-0'); // Ajoute la classe pour faire le glissement
            flashMessage.classList.add('shadow-lg'); // Ajoute une ombre pour le rendre plus visible
        }

        // Fonction pour masquer le message avec une transition
        function hideFlashMessage() {
            const flashMessage = document.getElementById('flash-message');
            flashMessage.classList.remove('translate-y-0'); // Enlève la classe pour cacher l'élément
            flashMessage.classList.add('-translate-y-full'); // Ajoute la classe pour remonter le message
            flashMessage.classList.remove('shadow-lg'); // Enlève l'ombre pour le rendre moins visible

        }

        // Montre le message après un court délai pour l'animation
        window.onload = function() {
            showFlashMessage();

            // Masque le message après 5 secondes
            setTimeout(function() {
                hideFlashMessage();
            }, 5000); // 5000 millisecondes = 5 secondes
        };
    </script>
@endif


        @if (session('error'))
            <div class="fixed top-0 left-1/2 transform -translate-x-1/2 bg-red-500 text-white p-4 rounded shadow-lg z-50 mt-4">
                <div class="container mx-auto flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white font-bold ml-3">X</button>
                </div>
            </div>
        @endif
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow flex ">
                    <a href="{{ url()->previous() }}" onclick="window.history.go(-1); return false;" class="block p-4 sm:p-6 lg:p-8 px-1.5 hover:bg-gray-100 hover:dark:bg-gray-700">
                        <x-icon size="1" type="arrow_back" class="fill-gray-500 dark:fill-gray-300" />
                    </a>
                    <div class="w-5/6 ml-0 py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>

</html>

