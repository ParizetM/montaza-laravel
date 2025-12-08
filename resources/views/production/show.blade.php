<x-app-layout>
    @section('title', 'Affaire ' . $affaire->code)
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $affaire->nom }} <span class="text-gray-500 text-sm font-normal">({{ $affaire->code }})</span>
                </h2>
                <div class="mt-1 flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $affaire->statut_color }}-100 text-{{ $affaire->statut_color }}-800 dark:bg-{{ $affaire->statut_color }}-900 dark:text-{{ $affaire->statut_color }}-200">
                        {{ $affaire->statut_label }}
                    </span>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('affaires.edit', $affaire) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Modifier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Budget</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($affaire->budget, 2, ',', ' ') }} €</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Engagé (CDE)</div>
                    <div class="mt-1 text-2xl font-semibold {{ $affaire->total_ht > $affaire->budget ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($affaire->total_ht, 2, ',', ' ') }} €
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Commandes</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $affaire->cdes->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Matériels</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $affaire->materiels->count() }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Section Commerciale -->
                <div class="space-y-6">
                    <!-- Commandes -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Commandes Fournisseurs</h3>
                            <a href="{{ route('cde.create', ['affaire_id' => $affaire->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">Nouvelle CDE</a>
                        </div>
                        <div class="p-6">
                            @if($affaire->cdes->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune commande liée.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->cdes as $cde)
                                        <li class="py-3 flex justify-between items-center">
                                            <div>
                                                <a href="{{ route('cde.show', $cde) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">{{ $cde->code }}</a>
                                                <p class="text-xs text-gray-500">{{ $cde->societe?->raison_sociale ?? 'Fournisseur inconnu' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ number_format($cde->total_ht, 2, ',', ' ') }} €</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $cde->statut->nom ?? '-' }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Demandes de Prix -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Demandes de Prix</h3>
                            <a href="{{ route('ddp.create', ['affaire_id' => $affaire->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">Nouvelle DDP</a>
                        </div>
                        <div class="p-6">
                            @if($affaire->ddps->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune demande de prix liée.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->ddps as $ddp)
                                        <li class="py-3 flex justify-between items-center">
                                            <div>
                                                <a href="{{ route('ddp.show', $ddp) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">{{ $ddp->code }}</a>
                                                <p class="text-xs text-gray-500">{{ $ddp->nom }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $ddp->statut->nom ?? '-' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Section Technique -->
                <div class="space-y-6">
                    <!-- Matériel -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Matériel Assigné</h3>
                            <!-- Bouton d'ajout de matériel à implémenter -->
                        </div>
                        <div class="p-6">
                            @if($affaire->materiels->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun matériel assigné.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->materiels as $materiel)
                                        <li class="py-3 flex justify-between items-center">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $materiel->designation }}</span>
                                                <p class="text-xs text-gray-500">{{ $materiel->numero_serie }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs text-gray-500">Du {{ $materiel->pivot->date_debut }} au {{ $materiel->pivot->date_fin }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Réparations -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Réparations / SAV</h3>
                        </div>
                        <div class="p-6">
                            @if($affaire->reparations->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune réparation liée.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->reparations as $reparation)
                                        <li class="py-3 flex justify-between items-center">
                                            <div>
                                                <a href="{{ route('reparation.show', $reparation) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Réparation #{{ $reparation->id }}</a>
                                                <p class="text-xs text-gray-500">{{ $reparation->materiel->designation }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $reparation->status }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
