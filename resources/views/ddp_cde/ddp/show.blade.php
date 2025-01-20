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

                thead {
                    background: none;
                }
            </style>
            <div id="handsontable-container" class="ht-theme-main-dark-auto"></div>
            <button id="export-string" class="btn">Export as a string</button>


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
                                correctFormat: true,
                                datePickerConfig: {
                                    firstDay: 1,
                                    showWeekNumber: true,
                                    numberOfMonths: 1,
                                    i18n: {
                                        previousMonth: 'Mois précédent',
                                        nextMonth: 'Mois suivant',
                                        months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
                                            'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                                        ],
                                        weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi',
                                            'Samedi'
                                        ],
                                        weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
                                    }
                                },
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
                                '=SUM({{ chr(65 + $loop->index * 2) }}2:{{ chr(65 + $loop->index * 2) }}{{ $ddp->ddpLigne->count() + 1 }})',
                                '=IF(MINIFS({{ chr(66 + $loop->index * 2) }}2:{{ chr(66 + $loop->index * 2) }}{{ $ddp->ddpLigne->count() + 1 }}, {{ chr(66 + $loop->index * 2) }}2:{{ chr(66 + $loop->index * 2) }}{{ $ddp->ddpLigne->count() + 1 }}, ">=" & TODAY())=0, "", MINIFS({{ chr(66 + $loop->index * 2) }}2:{{ chr(66 + $loop->index * 2) }}{{ $ddp->ddpLigne->count() + 1 }}, {{ chr(66 + $loop->index * 2) }}2:{{ chr(66 + $loop->index * 2) }}{{ $ddp->ddpLigne->count() + 1 }}, ">=" & TODAY()))',
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

                    function findMinValue(rowData) {
                        const numericValues = rowData.filter(value => typeof value === 'number' && !isNaN(value));
                        return Math.min(...numericValues);
                    };
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
                        contextMenu: false,
                        formulas: {
                            engine: HyperFormula,
                        },
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
                                cellProperties.renderer = function(instance, td, row, col, prop, value,
                                    cellProperties) {
                                    Handsontable.renderers.TextRenderer.apply(this, arguments);
                                    td.style.fontWeight = 'bold';
                                    td.title = 'Date la plus proche';
                                };
                            }
                            cellProperties.renderer = function(instance, td, row, col, prop, value) {
                                Handsontable.renderers.TextRenderer.apply(this, arguments);
                                const rowData = instance.getDataAtRow(
                                    row); // Obtenir toutes les valeurs de la ligne
                                const minValue = findMinValue(rowData); // Trouver la valeur minimale
                                if (value === minValue && typeof value === 'number' && value !== 0) {
                                    td.style.backgroundColor = '#77DD77'; // Changer le fond en vert
                                    td.style.color = '#145214'; // Changer la couleur du texte
                                }
                            };

                            return cellProperties;
                        },
                    });
                    const exportPlugin = hot.getPlugin('exportFile');
                    const button = document.querySelector('#export-string');

                    button.addEventListener('click', () => {
                        const exportedString = exportPlugin.exportAsString('csv', {
                            bom: false,
                            columnDelimiter: ',',
                            columnHeaders: false,
                            exportHiddenColumns: true,
                            exportHiddenRows: true,
                            rowDelimiter: '\r\n',
                            rowHeaders: false,
                        });
                        console.log(exportedString);
                        fetch('/ddp/{{ $ddp->id }}/save-retours', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ data: exportedString })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success:', data);
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                    });

                });
            </script>
        </div>
    </div>




</x-app-layout>
