<x-app-layout>
    <div class=" flex items-center justify-center h-screen">
        <div class="text-center">
            <h1 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Bienvenue à Montaza</h1>
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-8">Votre plateforme de gestion préférée</p>
            <a href="{{ url('/dashboard') }}"
                class="bg-gray-800 px-6 py-3 rounded-full text-lg font-medium text-gray-900 dark:text-gray-100">
                @if (Auth::check())
                    {{ __('Dashboard') }}
                @else
                    {{ __('Get Started') }}
                @endif
            </a>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">
                {{ __('Profile Information') }}
            </h2>
        </div>
    </div>
</x-app-layout>
