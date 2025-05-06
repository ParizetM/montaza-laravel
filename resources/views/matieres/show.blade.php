<x-app-layout>
    @section('title', 'Matières - ' . $matiere->designation)

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Matières</a>
                >>
                <a href="{{ route('matieres.show', $matiere->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une demande de prix') !!}</a>
                >> {{ $matiere->designation }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6 space-y-6">
        <!-- Carte d'information principale -->
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-gray-100 dark:to-gray-300 bg-clip-text text-transparent">{{ $matiere->designation }}</h1>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-full px-5 py-2 flex items-center gap-2 shadow-inner text-sm font-medium">
                    <span class="text-gray-500 dark:text-gray-400">Référence:</span>
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $matiere->ref_interne }}</span>
                </div>
            </div>

            <!-- Infos principales en grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Sous Famille</p>
                    <p class="font-semibold text-lg">{{ $matiere->sousFamille->nom }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Matière</p>
                    <p class="font-semibold text-lg">{{ $matiere->material->nom ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Stock actuel</p>
                    <p class="font-semibold text-lg"><x-stock-tooltip matiereId="{{ $matiere->id }}" /></p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Référence unitaire</p>
                    <p class="font-semibold text-lg">{{ $matiere->ref_valeur_unitaire }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">DN</p>
                    <p class="font-semibold text-lg">{{ $matiere->dn }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Épaisseur</p>
                    <p class="font-semibold text-lg">{{ $matiere->epaisseur ?? '-' }}</p>
                </div>
            </div>

            @if ($matiere->standardVersion != null)
            <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-blue-100 dark:border-blue-800 transition-all duration-300 hover:shadow-md">
                <x-icons.pdf class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Standard</p>
                    <a href="{{ $matiere->standardVersion->chemin_pdf ?? '-' }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200" target="_blank">
                        {{ $matiere->standardVersion->standard->nom ?? '-' }} - {{ $matiere->standardVersion->version ?? '-' }}
                    </a>
                </div>
            </div>
            @else
            <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg mb-6 border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Standard</p>
                <p class="font-semibold">Aucun standard</p>
            </div>
            @endif
        </div>

        <!-- Grille avec 2 colonnes pour fournisseurs et mouvements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Carte des fournisseurs -->
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Fournisseurs</h2>
                </div>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-750">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Référence</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fournisseur</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dernier prix</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($fournisseurs as $fournisseur)
                                <tr @if ($fournisseur->prix != null && $fournisseur->prix->prix_unitaire != null)
                                    onclick="window.location.href = '{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}';"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200"
                                    @else
                                    class=""
                                    @endif>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $fournisseur->ref_externe ?? 'Aucune référence' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap font-medium">{{ $fournisseur->raison_sociale }}</td>
                                    @if ($fournisseur->prix != null && $fournisseur->prix->prix_unitaire != null)
                                        <td class="px-4 py-3 whitespace-nowrap font-semibold text-green-600 dark:text-green-400">
                                            {{ formatNumberArgent($fournisseur->prix->prix_unitaire) . '/' . $matiere->unite->short }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ formatDate(date_string: $fournisseur->prix->date) }}
                                        </td>
                                    @else
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400" colspan="2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                Aucun prix
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @if ($fournisseurs->count() == 0)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400" colspan="4">Aucun fournisseur pour le moment</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Carte des mouvements de stock -->
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-emerald-100 dark:bg-emerald-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Mouvements de stock</h2>
                </div>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-750">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mouvement</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($matiere->mouvementStocks && $matiere->mouvementStocks->count() > 0)
                                @php
                                    $mouvements = $matiere->mouvementStocks->sortByDesc('created_at');
                                @endphp
                                @foreach ($mouvements as $mouvement)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($mouvement->type_mouvement == 'entree')
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-red-100 text-red-500 dark:bg-red-900 dark:text-red-300 mr-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </span>
                                                    <span class="text-red-500 dark:text-red-400 font-medium">- {{ $mouvement->quantite }}</span>
                                                    @if ($mouvement->valeur_unitaire != null)
                                                        <span class="text-red-500 dark:text-red-400 ml-1">x
                                                            ({{ formatNumber($mouvement->valeur_unitaire) . ' ' . $matiere->unite->short }})</span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-500 dark:bg-green-900 dark:text-green-300 mr-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                        </svg>
                                                    </span>
                                                    <span class="text-green-500 dark:text-green-400 font-medium">+ {{ $mouvement->quantite }}</span>
                                                    @if ($mouvement->valeur_unitaire != null)
                                                        <span class="text-green-500 dark:text-green-400 ml-1">x
                                                            ({{ formatNumber($mouvement->valeur_unitaire) . ' ' . $matiere->unite->short }})</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ $mouvement->created_at->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400" colspan="2">Aucun mouvement</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Formulaire de retrait -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-red-100 dark:bg-red-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Retirer matière</h2>
                </div>
                <form action="{{ route('matieres.mouvement', $matiere->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('POST')
                    <div>
                        <x-input-label value="Quantité à retirer" class="text-base mb-1" />
                        <div class="flex items-center gap-3">
                            <x-text-input type="number" name="quantite" class="w-1/2 focus:ring-red-500 focus:border-red-500" value="{{ old('quantite') }}" placeholder="Saisir une quantité" />
                            <span class="text-gray-500 dark:text-gray-400">{{ $matiere->unite->short }}</span>
                        </div>
                        <input type="hidden" name="type" value="0">
                        @error('quantite')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-800/50 flex items-center gap-2 transform transition-transform duration-200 hover:scale-[1.02] active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-red-600 dark:text-red-400">Retirer du stock</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Graphique d'évolution du stock -->
        @if ($dates == null)
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-yellow-100 dark:bg-yellow-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-yellow-600 dark:text-yellow-400 font-medium">Aucun mouvement pour cette matière</p>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Évolution du stock</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                        <x-input-label for="startDate" class="block mb-2">Date de début :</x-input-label>
                        <select id="startDate" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($dates as $date)
                                <option value="{{ $date }}">{{ $date }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                        <x-input-label for="endDate" class="block mb-2">Date de fin :</x-input-label>
                        <select id="endDate" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($dates->reverse() as $date)
                                <option value="{{ $date }}">{{ $date }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="mb-6 chart-container" style="position: relative; height:300px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('myChart').getContext('2d');

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates),
                    datasets: [{
                        label: 'Quantité sur le temps',
                        data: @json($quantites),
                        borderColor: '#4F46E5', // Indigo-600
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true,
                        pointBackgroundColor: '#4F46E5',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'hour',
                                displayFormats: {
                                    hour: 'yyyy-MM-dd HH:mm',
                                },
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            type: 'linear',
                            grid: {
                                borderDash: [2]
                            }
                        }
                    }
                }
            });

            // Gestionnaires des menus déroulants
            const startDateSelect = document.getElementById('startDate');
            const endDateSelect = document.getElementById('endDate');

            // Fonction pour mettre à jour les limites de l'axe X
            const updateChartLimits = () => {
                const startDate = startDateSelect.value;
                const endDate = endDateSelect.value;

                if (new Date(startDate) <= new Date(endDate)) {
                    myChart.options.scales.x.min = startDate;
                    myChart.options.scales.x.max = endDate;
                    myChart.update();
                } else {
                    alert("La date de début doit être inférieure ou égale à la date de fin.");
                }
            };

            // Ajoute des événements de changement aux sélecteurs
            startDateSelect.addEventListener('change', updateChartLimits);
            endDateSelect.addEventListener('change', updateChartLimits);
        });
    </script>
    @endif
</x-app-layout>
