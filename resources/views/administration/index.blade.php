<x-app-layout>
    @section('title', 'Administration')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg p-4 flex flex-wrap gap-4">
                @can('gerer_les_utilisateurs')
                    <div class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700"
                        onclick="window.location='{{ route('profile.index') }}'">
                        <x-icons.group class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Utilisateurs') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les utilisateurs') }}</p>
                        </div>
                    </div>
                @endcan
                @can('gerer_les_permissions')
                    <div class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700"
                        onclick="window.location='{{ route('permissions') }}'">
                        <x-icons.key class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Permissions et Postes') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les permissions et les postes') }}</p>
                        </div>
                    </div>
                @endcan
                @can('voir_historique')
                    <div class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700"
                        onclick="window.location='{{ route('model_changes.index') }}'">
                        <x-icons.history class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Historique') }}</h1>
                            <p class="p-1 rounded-sm">{{ __('Voir l\'historique des modifications') }}</p>
                        </div>
                    </div>
                @endcan
                @can('gerer_mail_templates')
                    <div class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700"
                        onclick="window.location='{{ route('mailtemplates.index') }}'">
                        <x-icons.inbox-text class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Modèles de mail') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les modèles de mail') }}</p>
                        </div>
                    </div>
                @endcan
                @can('gerer_info_entreprise')
                    <div class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700"
                        onclick="window.location='{{ route('administration.info') }}'">
                        <x-icons.settings class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('informations entreprise') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les informations des entreprises') }}</p>
                        </div>
                    </div>
                @endcan
                <div class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700"
                        onclick="window.location='{{ route('administration.icons') }}'">
                        <x-icons.settings class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Icons') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Voir tout les icons utilisé') }}</p>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
