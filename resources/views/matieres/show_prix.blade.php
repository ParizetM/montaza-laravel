<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Matières</a>
                >>
                <a href="{{ route('matieres.show', $matiere->id) }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{{ $matiere->designation }}</a>
                >> Prix par fournisseur
            </h2>


            <div class="flex gap-4">
                <div>
                    <x-input-label for="startDate" class="block">Date de début :</x-input-label>
                    <select id="startDate" class="select w-auto">
                        @foreach ($dates->reverse() as $date)
                            <option value="{{ $date }}">{{ $date }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="endDate" class="block">Date de fin :</x-input-label>
                    <select id="endDate" class="select w-auto">
                        @foreach ($dates as $date)
                            <option value="{{ $date }}">{{ $date }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $matiere->designation }} - Prix
                    {{ $fournisseur->raison_sociale }}</h1>
            </div>
            <div class="block overflow-auto">
                @if ($fournisseurs_prix->count() == 0)
                    <p class="text-center">Aucun prix n'a été enregistré pour cette matière et ce fournisseur pour afficher un graphique.</p>
                @endif
                @if ($fournisseurs_prix->count() == 1)
                    <p class="text-center">Il faut plus d'un prix pour cette matière et ce fournisseur pour afficher un graphique.</p>
                @endif
                @if ($fournisseurs_prix->count() > 1)
                    <p class="text-center">Les prix sont affichés par ordre chronologique.</p>
                    <div class="mb-6 chart-container">
                        <canvas id="myChart" width="400" height="100"></canvas>
                    </div>
                @endif
                <table class="mt-6 min-w-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Dernier prix </th>
                            <th class="px-4 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fournisseurs_prix as $fournisseur_prix)
                            <tr class="">
                                <td class="border px-4 py-2 whitespace-nowrap">{{ $fournisseur_prix->pivot->prix }} €
                                </td>
                                <td class="border px-4 py-2 whitespace-nowrap">
                                    {{ $fournisseur_prix->pivot->date_dernier_prix }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('myChart').getContext('2d');

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates), // Limite à 10 dates passées par le contrôleur
                    datasets: [{
                        label: 'Prix sur le temps',
                        data: @json($prix), // Les valeurs passées par le contrôleur
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'white',
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time', // Échelle temporelle
                            time: {
                                unit: 'hour', // Regroupe par heure (modifiable selon vos besoins)
                                displayFormats: {
                                    hour: 'yyyy-MM-dd HH:mm', // Format d'affichage des dates et heures
                                },
                            },
                        },
                        y: {
                            beginAtZero: true,
                            type: 'linear',
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
                    myChart.options.scales.x.min = startDate; // Limite inférieure
                    myChart.options.scales.x.max = endDate; // Limite supérieure
                    myChart.update(); // Met à jour le graphique
                } else {
                    alert("La date de début doit être inférieure ou égale à la date de fin.");
                }
            };

            // Ajoute des événements de changement aux sélecteurs
            startDateSelect.addEventListener('change', updateChartLimits);
            endDateSelect.addEventListener(
                'change', updateChartLimits);
        });
    </script>

</x-app-layout>
