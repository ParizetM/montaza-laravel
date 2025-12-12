<x-app-layout>
    @section('title', 'Affaires')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Affaires') !!}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center">
                <form method="GET" action="{{ route('affaires.index') }}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                    <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                        oninput="debounceSubmit(this.form)"
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">

                    <select name="statut" onchange="this.form.submit()" class="mt-2 sm:mt-0 sm:ml-4 px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                        <option value="">{{ __('Tous les statuts') }}</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>{{ __('En attente') }}</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                        <option value="archive" {{ request('statut') == 'archive' ? 'selected' : '' }}>{{ __('Archivé') }}</option>
                    </select>

                    <div class="flex items-center ml-4 my-1">
                        <label for="nombre" class="mr-2 text-gray-900 dark:text-gray-100">{{ __('Quantité') }}</label>
                        <input type="number" name="nombre" id="nombre"
                            value="{{ old('nombre', request('nombre', 50)) }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2">
                    </div>
                    <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                        {{ __('Rechercher') }}
                    </button>
                </form>
                <a href="{{ route('affaires.planning') }}" class="btn mb-1 mr-2 bg-indigo-600 hover:bg-indigo-700 text-white">
                    {{ __('Planning') }}
                </a>
                @can('gerer_les_affaires')
                    <button x-data="" class="btn mb-1"
                        x-on:click.prevent="$dispatch('open-modal', 'create-affaire-modal')">
                        {{ __('Nouvelle affaire') }}
                    </button>
                @endcan
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($affaires as $affaire)
                    <a href="{{ route('affaires.show', $affaire) }}" class="block group">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $affaire->statut_color }}-100 text-{{ $affaire->statut_color }}-800 dark:bg-{{ $affaire->statut_color }}-900 dark:text-{{ $affaire->statut_color }}-200">
                                            {{ $affaire->statut_label }}
                                        </span>
                                        <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $affaire->nom }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $affaire->code }}</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">Budget</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($affaire->budget, 2, ',', ' ') }} €</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">Réalisé</span>
                                        <span class="font-medium {{ $affaire->total_ht > $affaire->budget ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($affaire->total_ht, 2, ',', ' ') }} €
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $affaire->cdes->count() }} Commandes</span>
                                    <span>{{ $affaire->materiels->where('pivot.statut', '!=', 'termine')->count() }} Matériels</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-4 flex justify-center items-center pb-3 pagination">
                {{ $affaires->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <x-modal name="create-affaire-modal" :show="false" maxWidth="lg">
        <div class="p-4">
            <a x-on:click="$dispatch('close')">
                <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
            </a>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Nouvelle affaire</h2>
            <div id="create-affaire-modal-body">
                <div id="loading-spinner"
                    class=" m-6 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full">
                    <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div>
                </div>
                <style>
                    .loader {
                        border-top-color: #3498db;
                        animation: spinner 1.5s linear infinite;
                    }

                    @keyframes spinner {
                        0% {
                            transform: rotate(0deg);
                        }

                        100% {
                            transform: rotate(360deg);
                        }
                    }
                </style>

            </div>
        </div>
    </x-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            window.addEventListener('open-modal', function(e) {
                if (e.detail === 'create-affaire-modal') {
                    const modalBody = document.getElementById('create-affaire-modal-body');
                    fetch("{{ route('affaires.create') }}")
                        .then(response => response.text())
                        .then(html => {
                            modalBody.innerHTML = html;
                            attachCreateFormListener();
                        });
                }
            });
        });

        function attachCreateFormListener() {
            const form = document.getElementById('create-affaire-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.affaire) {
                                window.location.reload();
                            } else if (data.errors) {
                                let errorHtml = '<div class="text-red-500 mb-2">';
                                for (const key in data.errors) {
                                    errorHtml += data.errors[key].join('<br>') + '<br>';
                                }
                                errorHtml += '</div>';
                                form.insertAdjacentHTML('afterbegin', errorHtml);
                            }
                        });
                });
            }
        }

        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>
</x-app-layout>
