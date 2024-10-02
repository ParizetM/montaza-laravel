<x-app-layout>
    <div class=" flex justify-center pt-48">
        <div class="text-center pt-6 ">
            <h1 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Bienvenue</h1>
            <a href="{{ url('/dashboard') }}"
                class="btn bg-white dark:bg-gray-800 rounded-full p-4 text-base">
                @if (Auth::check())
                    {{ __('Dashboard') }}
                @else
                    {{ __('Log in') }}
                @endif
            </a>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8">
            </h2>
        </div>
    </div>
</x-app-layout>
