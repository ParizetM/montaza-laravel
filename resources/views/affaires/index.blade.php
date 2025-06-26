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
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
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
                @can('gerer_les_affaires')
                    <button x-data="" class="btn mb-1"
                        x-on:click.prevent="$dispatch('open-modal', 'create-affaire-modal')">
                        {{ __('Nouvelle affaire') }}
                    </button>
                @endcan
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 sm:rounded-lg shadow-md">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <table class="min-w-full bg-white dark:bg-gray-800" id="affaires-table">
                            <thead
                                class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800 text-gray-700 dark:text-gray-100">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Code</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nom</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                                        <div class="flex items-center flex-col w-fit">
                                            <div
                                                class="border-b-2 border-gray-500 dark:border-gray-400 text-gray-700 dark:text-gray-300">
                                                <p class="font-semibold text-lg">
                                                    Total HT
                                                </p>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 ">
                                                Budget
                                            </p>
                                        </div>
                                    </th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Date de création
                                    </th>
                                    <th
                                        class="text-left py-3 px-4 uppercase font-semibold text-sm flex justify-between items-center">
                                        Actions
                                        <x-tooltip position="left">
                                            <x-slot:slot_item>

                                                <a href="{{ route('affaires.actualiser_totals') }}"
                                                    class="flex items-center justify-center aspect-square rounded-full text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 overflow-hidden transition p-1">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </x-slot:slot_item>
                                            <x-slot:slot_tooltip>
                                                <p>Actualiser les budget</p>
                                            </x-slot:slot_tooltip>
                                        </x-tooltip>

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                                id="affaires-tbody">
                                @forelse ($affaires as $affaire)
                                    <tr class=" transition-all duration-200 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 my-2"
                                        id="affaire-row-{{ $affaire->id }}">
                                        <td
                                            class="py-3 px-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $affaire->code }}</td>
                                        <!-- nom de l'affaire -->
                                        <td
                                            class="py-3 px-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $affaire->nom }}</td>
                                        <td class="py-2 px-4">
                                            <div class="flex items-center flex-col w-fit">
                                                <div
                                                    class="border-b-2 border-gray-500 dark:border-gray-400 text-gray-700 dark:text-gray-300">
                                                    <p
                                                        class="font-semibold text-lg
                                                        @if ($affaire->total_ht > $affaire->budget) text-orange-500 dark:text-orange-400 @endif
                                                    ">
                                                        {{ formatNumberArgent($affaire->total_ht) }}
                                                    </p>
                                                </div>
                                                <p class="text-gray-500 dark:text-gray-400 ">
                                                    {{ formatNumberArgent($affaire->budget) }}
                                                </p>
                                            </div>
                                        </td>
                                        <td
                                            class="py-3 px-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $affaire->created_at->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('affaires.show', $affaire->id) }}" class="btn-sm"
                                                    title="Voir">
                                                    Voir
                                                </a>
                                                @can('gerer_les_affaires')
                                                    <a href="{{ route('affaires.edit', $affaire->id) }}" class="btn-sm"
                                                        title="Éditer">
                                                        <x-icon type="edit" size="1.5" />
                                                    </a>
                                                    <x-boutons.supprimer :formAction="route('affaires.destroy', $affaire->id)" modalTitle="Supprimer l'affaire"
                                                        userInfo="Voulez-vous vraiment supprimer cette affaire ?"
                                                        :onSubmit="'deleteAffaireAjax(event,' . $affaire->id . ')'" errorName="delete-affaire-{{ $affaire->id }}"
                                                        modalName="delete-affaire-modal-{{ $affaire->id }}">
                                                        <x-slot:customButton>
                                                            <button type="button" class="btn-sm" title="Supprimer">
                                                                <x-icon type="delete" size="1.5" />
                                                            </button>
                                                        </x-slot:customButton>
                                                    </x-boutons.supprimer>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="text-center py-3 px-4 text-gray-900 dark:text-gray-100">Aucune
                                            affaire trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 flex justify-center items-center pb-3 pagination">
                    {{ $affaires->appends(request()->query())->links() }}
                </div>
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
                                addAffaireRow(data.affaire);
                                window.dispatchEvent(new CustomEvent('close-modal', {
                                    detail: 'create-affaire-modal'
                                }));
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

        function addAffaireRow(affaire) {
            const tbody = document.getElementById('affaires-tbody');
            const tr = document.createElement('tr');
            tr.className =
                'transition-all duration-200 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 my-2';
            tr.id = 'affaire-row-' + affaire.id;

            // Format numbers as currency (replace with your own formatting if needed)
            function formatNumberArgent(number) {
                return new Intl.NumberFormat('fr-FR', {
                    style: 'currency',
                    currency: 'EUR',
                    minimumFractionDigits: 2
                }).format(number);
            }

            // Highlight if total_ht > budget
            const totalHtClass = affaire.total_ht > affaire.budget ?
                'text-orange-500 dark:text-orange-400' :
                '';

            tr.innerHTML = `
                <td class="py-3 px-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                    ${affaire.code}
                </td>
                <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                    ${affaire.nom}
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center flex-col w-fit">
                        <div class="border-b-2 border-gray-500 dark:border-gray-400 text-gray-700 dark:text-gray-300">
                            <p class="font-semibold text-lg ${totalHtClass}">
                                ${formatNumberArgent(affaire.total_ht)}
                            </p>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 ">
                            ${formatNumberArgent(affaire.budget)}
                        </p>
                    </div>
                </td>
                <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                    ${affaire.created_at_formatted ?? affaire.created_at}
                </td>
                <td class="py-3 px-4 whitespace-nowrap text-center">
                    <div class="flex justify-center gap-2">
                        <a href="/affaires/${affaire.id}" class="btn-sm" title="Voir">
                            Voir
                        </a>
                        @can('gerer_les_affaires')
                            <a href="/affaires/${affaire.id}/edit" class="btn-sm" title="Éditer">
                                <x-icon type="edit" size="1.5" />
                            </a>
                            <button type="button" class="btn-sm" title="Supprimer"
                                onclick="deleteAffaireAjax(event, ${affaire.id})">
                                <x-icon type="delete" size="1.5" />
                            </button>
                        @endcan
                    </div>
                </td>
            `;
            tbody.prepend(tr);
            // Reset the form after adding the new affaire
            const createForm = document.getElementById('create-affaire-form');
            if (createForm) {
                createForm.reset();
                const errorDiv = createForm.querySelector('.text-red-500');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        }

        function deleteAffaireAjax(event, affaireId) {
            event.preventDefault();
            fetch(`/affaires/${affaireId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById('affaire-row-' + affaireId);
                        if (row) row.remove();
                        window.dispatchEvent(new CustomEvent('close-modal'));
                    } else {
                        alert('Erreur lors de la suppression.');
                    }
                });
        }
    </script>
</x-app-layout>
