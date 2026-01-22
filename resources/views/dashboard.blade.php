<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Bonjour, {{ Auth::user()->first_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Voici un aperçu de l'activité récente.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6 px-4 sm:px-0">
                <!-- DDP Column -->
                <div id="ddp_container" class="min-h-[300px] flex flex-col">
                    <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-blue-100 dark:border-blue-900/50 h-full flex items-center justify-center">
                        <div class="flex flex-col items-center">
                            <!-- Spinner Container -->
                            <div class="relative w-16 h-16 mb-4">
                                <!-- Background Circle -->
                                <div class="absolute inset-0 border-4 border-blue-100 dark:border-blue-900/20 rounded-full"></div>
                                <!-- Spinning Arc -->
                                <div class="absolute inset-0 border-4 border-t-blue-500 border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                            </div>
                            <span class="text-sm font-bold text-blue-500/80 uppercase tracking-widest animate-pulse">Chargement DDP...</span>
                        </div>
                    </div>
                </div>

                <!-- CDE Column -->
                 <div id="cde_container" class="min-h-[300px] flex flex-col">
                    <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-green-100 dark:border-green-900/50 h-full flex items-center justify-center">
                        <div class="flex flex-col items-center">
                             <!-- Spinner Container -->
                            <div class="relative w-16 h-16 mb-4">
                                <!-- Background Circle -->
                                <div class="absolute inset-0 border-4 border-green-100 dark:border-green-900/20 rounded-full"></div>
                                <!-- Spinning Arc -->
                                <div class="absolute inset-0 border-4 border-t-green-500 border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                            </div>
                            <span class="text-sm font-bold text-green-500/80 uppercase tracking-widest animate-pulse">Chargement CDE...</span>
                        </div>
                    </div>
                </div>

                <!-- Affaires Column -->
                <div id="production_container" class="min-h-[300px] flex flex-col">
                    <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-purple-100 dark:border-purple-900/50 h-full flex items-center justify-center">
                        <div class="flex flex-col items-center">
                             <!-- Spinner Container -->
                            <div class="relative w-16 h-16 mb-4">
                                <!-- Background Circle -->
                                <div class="absolute inset-0 border-4 border-purple-100 dark:border-purple-900/20 rounded-full"></div>
                                <!-- Spinning Arc -->
                                <div class="absolute inset-0 border-4 border-t-purple-500 border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                            </div>
                            <span class="text-sm font-bold text-purple-500/80 uppercase tracking-widest animate-pulse">Chargement Affaires...</span>
                        </div>
                    </div>
                </div>

                 <!-- Devis Column -->
                <div id="devis_tuyauterie_container" class="min-h-[300px] flex flex-col">
                    <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-amber-100 dark:border-amber-900/50 h-full flex items-center justify-center">
                        <div class="flex flex-col items-center">
                             <!-- Spinner Container -->
                            <div class="relative w-16 h-16 mb-4">
                                <!-- Background Circle -->
                                <div class="absolute inset-0 border-4 border-amber-100 dark:border-amber-900/20 rounded-full"></div>
                                <!-- Spinning Arc -->
                                <div class="absolute inset-0 border-4 border-t-amber-500 border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                            </div>
                            <span class="text-sm font-bold text-amber-500/80 uppercase tracking-widest animate-pulse">Chargement Devis...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/colddp/small')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('ddp_container').innerHTML = data;
                });
        });
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/colaffaire/small')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('production_container').innerHTML = data;
                });
        });
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/colcde/small')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('cde_container').innerHTML = data;
                });
        });
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/coldevistuyauterie/small')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('devis_tuyauterie_container').innerHTML = data;
                });
        });
    </script>
</x-app-layout>
