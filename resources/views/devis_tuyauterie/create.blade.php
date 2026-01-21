<x-app-layout>
    @section('title', 'Créer un Devis')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un Devis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             @livewire('devis-tuyauterie-form')
        </div>
    </div>
</x-app-layout>
