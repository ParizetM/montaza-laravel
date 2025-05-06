<x-app-layout>
    @section('title', $matiere->designation . ' - Prix ' . $fournisseur->raison_sociale)

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Matières</a>
                >>
                <a href="{{ route('matieres.show', $matiere->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{{ $matiere->designation }}</a>
                >> Prix par fournisseur
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-gray-100 dark:to-gray-300 bg-clip-text text-transparent">Évolution des prix</h1>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-full px-5 py-2 flex items-center gap-2 shadow-inner text-sm font-medium">
                    <span class="text-gray-500 dark:text-gray-400">Fournisseur:</span>
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $fournisseur->raison_sociale }}</span>
                </div>
            </div>

            <!-- Grille pour les filtres de date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                    <x-input-label for="startDate" class="block mb-2 text-sm text-gray-500 dark:text-gray-400">Date de début :</x-input-label>
                    <select id="startDate" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($dates->reverse() as $date)
                            <option value="{{ $date }}">{{ formatDate($date) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                    <x-input-label for="endDate" class="block mb-2 text-sm text-gray-500 dark:text-gray-400">Date de fin :</x-input-label>
                    <select id="endDate" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($dates as $date)
                            <option value="{{ $date }}">{{ formatDate($date) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="bg-gray-50 dark:bg-gray-750 p-6 rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-emerald-100 dark:bg-emerald-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">{{ $matiere->designation }} - Prix {{ $fournisseur->raison_sociale }}</h2>
                </div>

                @if ($fournisseurs_prix->count() == 0)
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-yellow-100 dark:border-yellow-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-yellow-600 dark:text-yellow-400 font-medium">Aucun prix n'a été enregistré pour cette matière et ce fournisseur.</p>
                    </div>
                @elseif ($fournisseurs_prix->count() == 1)
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-blue-100 dark:border-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-blue-600 dark:text-blue-400 font-medium">Il faut plus d'un prix pour cette matière et ce fournisseur pour afficher un graphique.</p>
                    </div>
                @else
                    <p class="text-center text-gray-500 dark:text-gray-400 mb-4">Les prix sont affichés par ordre chronologique.</p>
                    <div class="mb-6 chart-container" style="position: relative; height:300px;">
                        <canvas id="myChart"></canvas>
                    </div>
                @endif

                <!-- Tableau des prix -->
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($fournisseurs_prix as $fournisseur_prix)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-4 py-3 whitespace-nowrap font-semibold text-green-600 dark:text-green-400">
                                        {{ formatNumberArgent($fournisseur_prix->prix_unitaire) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ formatDate($fournisseur_prix->date) }}
                                    </td>
                                </tr>
                            @endforeach
                            @if ($fournisseurs_prix->count() == 0)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400" colspan="2">
                                        Aucun prix enregistré
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if($fournisseurs_prix->count() > 1)
            const ctx = document.getElementById('myChart').getContext('2d');

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates),
                    datasets: [{
                        label: 'Prix sur le temps',
                        data: @json($prix),
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
            @endif
        });
    </script>
</x-app-layout>
