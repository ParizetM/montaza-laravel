<x-app-layout>
    <x-slot name="header">
        <div>
            {{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix et commandes</a>
                >>
                <a href="{{ route('ddp.show', $ddp->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une demande de prix') !!}</a>
                >> Validation
            </h2> --}}
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $matiere->designation }}</h1>

            </div>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Référence Interne</th>
                        <th class="px-4 py-2">Sous Famille</th>
                        <th class="px-4 py-2">Matière</th>
                        <th class="px-4 py-2">Désignation</th>
                        <th class="px-4 py-2">Qté</th>
                        <th class="px-4 py-2">Standard</th>
                        <th class="px-4 py-2">DN</th>
                        <th class="px-4 py-2">Ép</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">{{ $matiere->ref_interne }}</td>
                        <td class="border px-4 py-2">{{ $matiere->sousFamille->nom }}</td>
                        <td class="border px-4 py-2">{{ $matiere->material->nom ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $matiere->designation }}</td>

                        <td class="border px-4 py-2"><x-stock-tooltip matiereId="{{ $matiere->id }}" /></td>
                        <td class="border px-4 py-2 ">
                            @if ($matiere->standardVersion == null)
                                <span class="">Aucun standard</span>
                            @else
                                <div class="flex items-center">
                                    <x-icons.pdf class="w-6 h-6" />
                                    <a href="{{ $matiere->standardVersion->chemin_pdf ?? '-' }}" class="lien"
                                        target="_blank">
                                        {{ $matiere->standardVersion->standard->nom ?? '-' }} -
                                        {{ $matiere->standardVersion->version ?? '-' }}
                                    </a>
                                </div>
                            @endif

                        </td>
                        <td class="border px-4 py-2">{{ $matiere->dn }}</td>
                        <td class="border px-4 py-2">{{ $matiere->epaisseur ?? '-' }}</td>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-between flex-row-reverse">
                <div>
                    <table class="mt-6 min-w-0 min-y-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Référence</th>
                                <th class="px-4 py-2">Fournisseur</th>
                                <th class="px-4 py-2">Dernier prix </th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fournisseurs as $fournisseur)
                                <tr onclick="window.location.href = '{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}';"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer h-fit">
                                    <td class="border px-4 py-2 whitespace-nowrap">{{ $fournisseur->ref_externe ?? 'Aucune référence' }}
                                    </td>
                                    <td class="border px-4 py-2 whitespace-nowrap">{{ $fournisseur->raison_sociale }}
                                    </td>
                                    @if ($fournisseur->prix->prix_unitaire != null)
                                        <td class="border px-4 py-2 whitespace-nowrap">
                                            {{ formatNumberArgent($fournisseur->prix->prix_unitaire) . '/' . $matiere->unite->short }}
                                        </td>
                                        <td class="border px-4 py-2 whitespace-nowrap">
                                            {{ formatDate(date_string: $fournisseur->prix->date) }}</td>
                                    @else
                                        <td class="border px-4 py-2 whitespace-nowrap" colspan="2">Aucun prix</td>
                                    @endif
                                </tr>
                            @endforeach
                            @if ($fournisseurs->count() == 0)
                                <tr>
                                    <td class="border px-4 py-2 whitespace-nowrap" colspan="3">Aucun fournisseur pour
                                        le moment</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div>
                    <table class="mt-6 min-w-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">mouvement</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($matiere->mouvements && $matiere->mouvements->count() > 0)
                                @foreach ($matiere->mouvements as $mouvement)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                        <td class="border px-4 py-2 whitespace-nowrap">
                                            @if ($mouvement->type_mouvement == 0)
                                                <span class="text-red-500">- {{ $mouvement->quantite }}</span>
                                            @else
                                                <span class="text-green-500">+ {{ $mouvement->quantite }}</span>
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2 whitespace-nowrap">
                                            {{ $mouvement->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border px-4 py-2 whitespace-nowrap" colspan="2">Aucun mouvement</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="w-1/3">
                <form action="{{ route('matieres.mouvement', $matiere->id) }}" method="POST" class="mt-6">
                    @csrf
                    @method('POST')
                    <div class="flex flex-col">
                        <h2 class="text-xl font-bold mb-2">retirer matière</h2>
                        <x-input-label value="quantité" />
                        <x-text-input type="number" name="quantite" class="w-1/2" value="{{ old('quantite') }}" />
                        <input type="hidden" name="type" value="0">
                        @error('quantite')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                        <button type="submit" class="btn w-fit mt-2">retirer</button>

                    </div>
                </form>
            </div>
            @if ($dates == null)
                <div class="mt-6">
                    <p class="text-red-500">Aucun mouvement pour cette matière</p>
                </div>
            @else
                <div>
                    <div>
                        <x-input-label for="startDate" class="block">Date de début :</x-input-label>
                        <select id="startDate" class="select w-auto">
                            @foreach ($dates as $date)
                                <option value="{{ $date }}">{{ $date }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="endDate" class="block">Date de fin :</x-input-label>
                        <select id="endDate" class="select w-auto">
                            @foreach ($dates->reverse() as $date)
                                <option value="{{ $date }}">{{ $date }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6 chart-container">
                        <canvas id="myChart" width="400" height="100"></canvas>
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
                    labels: @json($dates), // Limite à 10 dates passées par le contrôleur
                    datasets: [{
                        label: 'quantité sur le temps',
                        data: @json($quantites), // Les valeurs passées par le contrôleur
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
    @endif
    </div>


</x-app-layout>
