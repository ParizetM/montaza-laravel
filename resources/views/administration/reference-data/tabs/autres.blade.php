<div class="space-y-6">
    <!-- Conditions de paiement -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Conditions de paiement</h3>
            <button x-data x-on:click="$dispatch('open-modal', 'create-condition-paiement')"
                    class="btn bg-blue-600 hover:bg-blue-700 text-white">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($conditionsPaiement as $condition)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $condition->nom }}</h4>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <button x-data x-on:click="$dispatch('open-modal', 'edit-condition-paiement-{{ $condition->id }}')"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Modifier">
                                    <x-icons.edit size="1.2" />
                                </button>
                                <button x-data x-on:click="$dispatch('open-modal', 'delete-condition-paiement-{{ $condition->id }}')"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        title="Supprimer">
                                    <x-icons.delete size="1.2" />
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $conditionsPaiement->appends(['tab' => 'autres'])->links() }}
            </div>
        </div>
    </div>

    <!-- Matériaux -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Matériaux</h3>
            <button x-data x-on:click="$dispatch('open-modal', 'create-material')"
                    class="btn bg-blue-600 hover:bg-blue-700 text-white">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($materials as $material)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $material->nom }}</h4>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <button x-data x-on:click="$dispatch('open-modal', 'edit-material-{{ $material->id }}')"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        title="Modifier">
                                    <x-icons.edit size="1.2" />
                                </button>
                                <button x-data x-on:click="$dispatch('open-modal', 'delete-material-{{ $material->id }}')"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        title="Supprimer">
                                    <x-icons.delete size="1.2" />
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $materials->appends(['tab' => 'autres'])->links() }}
            </div>
        </div>
    </div>

    <!-- Unités -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Unités</h3>
            <button x-data x-on:click="$dispatch('open-modal', 'create-unite')"
                    class="btn bg-blue-600 hover:bg-blue-700 text-white">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter
            </button>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Abréviation</th>
                            <th>Nom complet</th>
                            <th>Pluriel</th>
                            <th>Type</th>
                            <th>Matières</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($unites as $unite)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ $unite->short }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $unite->full }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $unite->full_plural ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $unite->type ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $unite->matieres_count ?: 0 }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button x-data x-on:click="$dispatch('open-modal', 'edit-unite-{{ $unite->id }}')"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                title="Modifier">
                                            <x-icons.edit size="1.2" />
                                        </button>
                                        <button x-data x-on:click="$dispatch('open-modal', 'delete-unite-{{ $unite->id }}')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Supprimer">
                                            <x-icons.delete size="1.2" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $unites->appends(['tab' => 'autres'])->links() }}
            </div>
        </div>
    </div>
</div>
