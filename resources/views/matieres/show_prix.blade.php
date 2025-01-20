<x-app-layout>
    <x-slot name="header">
        <div>
            {{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                <a href="{{ route('ddp.show', $ddp->id) }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{!! __('Créer une demande de prix') !!}</a>
                >> Validation
            </h2> --}}
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $matiere->designation }} - Prix
                    {{ $fournisseur->raison_sociale }}</h1>
            </div>
            <div class="block overflow-auto">

                <div class="mb-6 chart-container">
                    <canvas id="myChart"  width="400" height="100"></canvas>
                </div>
                <div class="flex">
                    <label for="startDate">Date de début :</label>
                    <select id="startDate" class="select w-auto">
                        @foreach ($dates as $date)
                            <option value="{{ $date }}">{{ $date }}</option>
                        @endforeach
                    </select>

                    <label for="endDate">Date de fin :</label>
                    <select id="endDate" class="select w-auto">
                        @foreach ($dates as $date)
                            <option value="{{ $date }}">{{ $date }}</option>
                        @endforeach
                    </select>
                </div>
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
            labels: @json($dates), // Les dates passées par le contrôleur
            datasets: [{
                label: 'Valeurs sur le temps',
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
                        unit: 'day', // Regroupe par jour
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
            myChart.options.scales.x.max = endDate;   // Limite supérieure
            myChart.update(); // Met à jour le graphique
        } else {
            alert("La date de début doit être inférieure ou égale à la date de fin.");
        }
    };

    // Ajoute des événements de changement aux sélecteurs
    startDateSelect.addEventListener('change', updateChartLimits);
    endDateSelect.addEventListener('change', updateChartLimits);
});

    </script>

</x-app-layout>
