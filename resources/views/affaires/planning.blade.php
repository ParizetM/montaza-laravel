<x-app-layout>
    @section('title', 'Planning Global des Affaires')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Planning Global') }}
            </h2>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('affaires.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filtres -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('affaires.planning') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div>
                        <label for="start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de début</label>
                        <input type="date" name="start" id="start" value="{{ $start->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="end" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin</label>
                        <input type="date" name="end" id="end" value="{{ $end->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Filtrer
                    </button>
                </form>
            </div>

            <!-- Gantt Chart -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <div class="min-w-[800px]">
                    <!-- Timeline Header -->
                    <div class="flex border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                        <div class="w-1/4 font-bold text-gray-700 dark:text-gray-300">Affaire</div>
                        <div class="w-3/4 relative h-6">
                            <div class="absolute left-0 text-xs text-gray-500">{{ $start->format('d/m/Y') }}</div>
                            <div class="absolute right-0 text-xs text-gray-500">{{ $end->format('d/m/Y') }}</div>
                            <!-- Grid lines could go here -->
                        </div>
                    </div>

                    <!-- Timeline Rows -->
                    <div class="space-y-4">
                        @forelse($planningData as $data)
                            <div class="flex items-center group">
                                <!-- Info Affaire -->
                                <div class="w-1/4 pr-4 truncate">
                                    <a href="{{ route('affaires.show', $data->affaire) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline" title="{{ $data->affaire->nom }}">
                                        {{ $data->affaire->code }}
                                    </a>
                                    <div class="text-xs text-gray-500 truncate">{{ $data->affaire->nom }}</div>
                                </div>

                                <!-- Barre Gantt -->
                                <div class="w-3/4 relative h-8 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <!-- La barre principale -->
                                    <div class="absolute h-full rounded-full flex items-center justify-center text-xs text-white font-semibold shadow-sm transition-all duration-300
                                        {{ $data->is_delayed ? 'bg-red-500' : 'bg-' . $data->affaire->statut_color . '-500' }}"
                                        style="left: {{ $data->left }}%; width: {{ $data->width }}%; min-width: 20px;
                                        {{ $data->is_delayed ? 'background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.2) 10px, rgba(255,255,255,0.2) 20px);' : '' }}"
                                        title="Du {{ $data->date_debut->format('d/m') }} au {{ $data->date_fin_effective->format('d/m') }} ({{ $data->affaire->statut_label }})">

                                        @if($data->width > 5)
                                            <span class="truncate px-2">{{ $data->affaire->statut_label }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Aucune affaire trouvée sur cette période.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Légende -->
            <div class="mt-6 flex gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-yellow-500 rounded"></div> En attente
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div> En cours
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div> Terminé
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-500 rounded"></div> Archivé
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-500" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,0.2) 5px, rgba(255,255,255,0.2) 10px);"></div> Retard (Prolongation auto)
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
