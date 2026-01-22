<x-app-layout>
    @section('title', 'Affaires')
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <span class="p-2 bg-purple-100 dark:bg-purple-900/30 text-purple-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                {{ __('Affaires') }}
            </h2>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <form method="GET" action="{{ route('affaires.index') }}" class="flex flex-col sm:flex-row gap-2 w-full md:w-auto items-center">
                    <div class="relative w-full sm:w-64">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" placeholder="Rechercher une affaire..." value="{{ request('search') }}"
                            oninput="debounceSubmit(this.form)"
                            class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100">
                    </div>

                    <select name="statut" onchange="this.form.submit()" class="w-full sm:w-40 rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100 text-sm">
                        <option value="">{{ __('Tous statuts') }}</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>{{ __('En attente') }}</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                        <option value="archive" {{ request('statut') == 'archive' ? 'selected' : '' }}>{{ __('Archivé') }}</option>
                    </select>
                </form>

                <div class="flex items-center gap-2">
                    <a href="{{ route('affaires.planning') }}" class="btn-secondary whitespace-nowrap flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Planning') }}
                    </a>

                    @can('gerer_les_affaires')
                        <a href="{{ route('affaires.create') }}" class="group inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200 shadow-sm whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Nouvelle affaire') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($affaires as $affaire)
                    <a href="{{ route('affaires.show', $affaire) }}" class="block group h-full">
                        <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-700 h-full flex flex-col">
                            <!-- Card Header -->
                            <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wide bg-{{ $affaire->statut_color }}-100 text-{{ $affaire->statut_color }}-700 dark:bg-{{ $affaire->statut_color }}-900/50 dark:text-{{ $affaire->statut_color }}-300">
                                                {{ $affaire->statut_label }}
                                            </span>
                                            <span class="text-xs text-gray-400 font-mono">{{ $affaire->code }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors line-clamp-2 leading-tight">
                                            {{ $affaire->nom }}
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 flex-1 flex flex-col justify-end space-y-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Budget</span>
                                        <span class="font-bold text-gray-900 dark:text-gray-100">{{ number_format($affaire->budget, 0, ',', ' ') }} €</span>
                                    </div>

                                    <div class="relative pt-1">
                                        <div class="overflow-hidden h-1.5 mb-1 text-xs flex rounded bg-gray-100 dark:bg-gray-700">
                                            @php
                                                $percentage = $affaire->budget > 0 ? min(100, ($affaire->total_ht / $affaire->budget) * 100) : 0;
                                                $barColor = $affaire->total_ht > $affaire->budget ? 'bg-red-500' : 'bg-green-500';
                                            @endphp
                                            <div style="width:{{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $barColor }}"></div>
                                        </div>
                                        <div class="flex justify-between items-center text-xs">
                                             <span class="text-gray-400">Consommé</span>
                                            <span class="font-bold {{ $affaire->total_ht > $affaire->budget ? 'text-red-600' : 'text-green-600' }}">
                                                {{ number_format($affaire->total_ht, 0, ',', ' ') }} €
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-1" title="Commandes">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $affaire->cdes->count() }}
                                </div>
                                <div class="flex items-center gap-1" title="Matériels">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                    {{ $affaire->materiels->where('pivot.statut', '!=', 'termine')->count() }}
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
</x-app-layout>
