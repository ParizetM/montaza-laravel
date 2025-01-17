<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                <a href="{{ route('ddp.show', $ddp->id) }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{!! __('Créer une demande de prix') !!}</a>
                >> Validation
            </h2>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $ddp->nom }} - Récapitulatif</h1>
                <a href="{{ route('ddp.pdfs.download', $ddp) }}" class="btn">Télécharger tous les PDF</a>

            </div>
            {{-- <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="bg-white dark:bg-gray-800"></th>
                        <th colspan="9" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fournisseurs</th>
                    </tr>
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matières</th>
                        @foreach ($ddp_societes as $societe)
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-white dark:bg-gray-800">{{ $societe->raison_sociale }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @for ($i = 1; $i <= 23; $i++)
                        <tr class="">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center bg-white dark:bg-gray-800">Matière {{ $i }}</td>
                            @foreach ($ddp_societes as $societe)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center bg-gray-100 dark:bg-gray-900">
                                    <x-text-input name="matiere_{{ $i }}_{{ $societe->id }}" type="text" class="w-20" />
                                    <x-date-input name="date_{{ $i }}_{{ $societe->id }}" class="w-20" />
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-sm text-gray-900 dark:text-gray-100 text-center">Total</td>
                        @foreach ($ddp_societes as $societe)
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-sm text-gray-900 dark:text-gray-100 text-center bg-gray-100 dark:bg-gray-900">23 €</td>
                        @endforeach
                    </tr>
                </tbody>
            </table> --}}
            <style>
                /* Style pour centrer le texte de la première ligne */
                .ht-center-first-row {
                    text-align: center;
                }
            </style>
            <div id="handsontable-container" class="ht-theme-main-dark-auto"></div>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                            const container = document.getElementById('handsontable-container');
                            // Variables de données
                            const rowHeaders = [
                                'Matières',
                                @foreach ($ddp->ddpLigne as $ddpLigne)
                                    "{{ $ddpLigne->matiere->designation }}",
                                @endforeach
                                'Total',
                                // Ajoutez autant de matières que nécessaire
                            ];
                            const ddp_societes = [
                                @foreach ($ddp_societes as $societe)
                                    {
                                        nom: "{{ $societe->raison_sociale }}",
                                        id: {{ $societe->id }}
                                    },
                                @endforeach
                                // Ajoutez autant de sociétés que nécessaire
                            ];

                            // Construire les en-têtes des colonnes
                            const colHeaders = [];
                            ddp_societes.forEach(societe => {
                                colHeaders.push(`(Prix €)`, `(Date)`);
                            });

                            // Construire les colonnes (read-only pour matières, saisie pour les autres)
                            const columns = [
                                ...ddp_societes.flatMap(() => [{
                                        type: 'numeric',
                                    }, // Colonne prix
                                    {
                                        type: 'date',
                                        dateFormat: 'DD/MM/YYYY',
                                        correctFormat: true
                                    } // Colonne date
                                ])
                            ];

                            // Construire les données
                            const data = [
                                [
                                    @foreach ($ddp_societes as $societe)
                                        '{{ $societe->raison_sociale }}',
                                        '{{ $societe->raison_sociale }}',
                                    @endforeach
                                ],
                                @foreach ($ddp->ddpLigne as $ddpLigne)
                                    [
                                        @foreach ($ddp_societes as $societe)
                                            '',
                                            '',
                                        @endforeach
                                    ],
                                @endforeach
                                [
                                    @foreach ($ddp_societes as $societe)
                                        '',
                                        '',
                                    @endforeach
                                ],
                            ];
                            const mergeCells = [
                                @foreach ($ddp_societes as $index => $societe)
                                    {
                                        row: 0,
                                        col: {{ $index * 2 }},
                                        rowspan: 1,
                                        colspan: 2
                                    },
                                @endforeach
                            ];
                            // Initialiser Handsontable
                            const hot = new Handsontable(container, {
                                data: data,
                                language: 'fr-FR', // Définir la langue sur français
                                local: frFR,
                                licenseKey: 'non-commercial-and-evaluation',
                                rowHeaders: rowHeaders,
                                rowHeaderWidth: 150,
                                colHeaders: colHeaders,
                                columns: columns,
                                mergeCells: mergeCells,
                                manualColumnResize: true,
                                manualRowResize: true,
                                contextMenu: true,
                                preventOverflow: 'horizontal',
                                colWidths(visualColumnIndex) {
                                    return visualColumnIndex % 2 === 0 ? 80 : 115;
                                },
                                autoColumnSize: {
                                    syncLimit: '100%'
                                },
                                cells: function(row, col, prop) {
                                    const cellProperties = {};
                                    if (row === 0) {
                                        cellProperties.readOnly = true;
                                        cellProperties.className += ' ht-center-first-row';
                                    };
                                    if (row == rowHeaders.length - 1) {
                                        cellProperties.readOnly = true;
                                    }
                                    return cellProperties;
                                },
                                afterChange: function(changes, source) {
                                    if (source === 'edit') {
                                        updateTotal();
                                    }
                                }
                            });

                            function updateTotal() {
                                // Récupère toutes les données de chaque colonne de montants
                                for (let col = 0; col < hot.countCols(); col += 2) {
                                    const columnData = hot.getDataAtCol(col);

                                    // Filtrer les valeurs numériques pour éviter les erreurs
                                    const total = columnData.reduce((sum, value, rowIndex) => {
                                        // Ignorer la première et la dernière ligne
                                        if (rowIndex === 0 || rowIndex === columnData.length - 1) {
                                            return sum;
                                        }
                                        // Vérifier si la valeur est numérique et ajouter à la somme
                                        const numericValue = parseFloat(value);
                                        if (!isNaN(numericValue)) {
                                            return sum + numericValue;
                                        }
                                        return sum;
                                    }, 0);

                                    // Afficher le total dans la cellule de la dernière ligne de la colonne
                                    const totalCell = hot.getCell(hot.countRows() - 1, col); // Dernière ligne, colonne de montants
                                    if (totalCell) {
                                        totalCell.readOnly = false;
                                        totalCell.innerHTML = numbro(total).format('0,0.00 €');
                                    }
                                }
                            }
                });
            </script>
        </div>
    </div>




</x-app-layout>
