<x-app-layout>
    @section('title', $affaire->code . ' ' . $affaire->nom)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('affaires.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Affaires</a>
                >> {{ $affaire->code }} — {{ $affaire->nom }}
            </h2>
            <div class="flex gap-2 flex-wrap">
                @can('gerer_les_affaires')
                    <a href="{{ route('affaires.edit', $affaire) }}" class="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6 space-y-6">
        <!-- Carte d'information principale -->
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 shadow-inner">
                        <x-icons.affaire size="2" class="fill-blue-600 dark:fill-blue-300" />
                    </div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-gray-100 dark:to-gray-300 bg-clip-text text-transparent">
                        {{ $affaire->code }} — {{ $affaire->nom }}
                    </h1>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-700 rounded-full px-5 py-2 flex items-center gap-2 shadow-inner text-sm font-medium">
                    <span class="text-gray-500 dark:text-gray-400">Créée le :</span>
                    <span
                        class="font-bold text-gray-900 dark:text-gray-100">{{ $affaire->created_at ? $affaire->created_at->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
            <!-- Infos principales en grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nombre de commandes</p>
                    <p class="font-semibold text-lg">{{ $affaire->cdes->count() }}</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="font-semibold text-lg">
                    <div class="flex items-center flex-col w-1/2">
                        <div
                            class="border-b-2 border-gray-500 dark:border-gray-400 text-gray-700 dark:text-gray-300 w-full">
                            <div
                                class="font-semibold text-lg flex items-center justify-between
                                                        @if ($affaire->total_ht > $affaire->budget) text-orange-500 dark:text-orange-400 @endif
                                                    ">
                                <span>Coût total </span><span> {{ formatNumberArgent($affaire->total_ht) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 w-full">
                            <span>Budget </span><span> {{ formatNumberArgent($affaire->budget) }}</span>
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>

        <!-- Tableau des commandes associées -->
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <h3 class="text-xl font-bold mb-4">Commandes associées</h3>
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-750">
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Numéro</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Date</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nom</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Statut</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Total_ht</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($affaire->cdes as $cde)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-4 py-3">{{ $cde->code }}</td>
                                <td class="px-4 py-3">{{ $cde->created_at ? $cde->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3">{{ $cde->nom }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold"
                                        style="background: {{ $cde->statut?->couleur ?? '#eee' }}; color: {{ $cde->statut?->couleur_texte ?? '#333' }}">
                                        {{ $cde->statut?->nom ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ formatNumberArgent($cde->total_ht) }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('cde.show', $cde->id) }}" class="btn-sm" target="_blank" title="Voir la commande">
                                        <x-icon size="1" type="open_in_new" class="icons" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 px-4 text-gray-900 dark:text-gray-100">Aucune
                                    commande associée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
