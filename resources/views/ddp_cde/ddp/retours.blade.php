<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('ddp_cde.index') }}"
                        class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                    >>
                    <a href="{{ route('ddp.show', $ddp->id) }}"
                        class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{!! __('Créer une demande de prix') !!}</a>
                    >> Retours
                </h2>

            </div>
            <a href="{{ route('ddp.pdfs.download', $ddp) }}" class="btn">Télécharger tous les PDF</a>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold mb-6 text-left">{{ $ddp->nom }} - Récapitulatif</h1>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Ordre des colonnes : REF FOURNISSEUR <strong class="dark:text-white text-gray-900">|</strong> PU HT <strong class="dark:text-white text-gray-900">|</strong> UNITE <strong class="dark:text-white text-gray-900">|</strong> DATE DE LIVRAISON POSSIBLE</p>
                </div>
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 flex items-center hidden"
                        title="Demande de prix en cours d'enregistrement" id="save-status-0">Enregistrement
                        en
                        cours...<x-icons.progress-activity size="2" /></h1>
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($ddp) ? '' : 'hidden' }}"
                        title="Demande de prix enregistré avec succès" id="save-status-1">Enregistré</h1>
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($ddp) ? 'hidden' : '' }}"
                        title="Demande de prix non enregistrée" id="save-status-2">Non-enregistré</h1>
                    <button class="" id="refresh">
                        <x-icons.refresh size="2" class="icons" />
                    </button>
                </div>
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

                .rowHeader {
                    text-align: left !important;
                }
            </style>
            <div id="handsontable-container" class="ht-theme-main-dark-auto"></div>
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('ddp.cancel_validate', $ddp->id) }}" class="btn float-right">Retour</a>
                <a href="{{ route('ddp.terminer', $ddp->id) }}" class="btn float-right">Terminer</a>
            </div>
        </div>
    </div>
    <script>
        const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.addEventListener('DOMContentLoaded', function() {
            var last_data;
            const container = document.getElementById('handsontable-container');
            // const lignes_count = {{ $ddp->ddpLigne->count() }};
            const societe_count = {{ $ddp_societes->count() }};
            // console.log('nombre de ligne : ' + lignes_count, 'nombre de societe : ' + societe_count);
            // Variables de données
            const rowHeaders = [
                'Matières',
                @foreach ($ddp->ddpLigne as $ddpLigne)
                    "{{ $ddpLigne->matiere->ref_interne }} {{ $ddpLigne->matiere->designation }}",
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
                colHeaders.push(`Réf`,`PU HT`,`U`,`DDL`);
            });

            // Construire les colonnes (read-only pour matières, saisie pour les autres)
            const columns = [
                ...ddp_societes.map(() => [{
                        type: 'text',
                    },
                    {
                        type: 'numeric',
                    }, // Colonne prix
                    {
                        type: 'select',
                        selectOptions: ['t', 'kg', 'ml', 'u', 'm²', 'm³', 'l'],
                    },
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
                ]).flat()
            ];

            const borders = [];

            // Générer les bordures toutes les deux colonnes
            for (let col = 0; col < (societe_count * 4); col += 4) {
                borders.push({
                    range: {
                        from: {
                            row: 0,
                            col: col
                        },
                        to: {
                            row: rowHeaders.length - 1,
                            col: col
                        },
                    },
                    start: {
                        width: 2,
                        color: 'gray',
                    },
                });
            }




            // Construire les données
            const data = @json($data);
            console.log(data);
            const mergeCells = [];
            for (let i = 0; i < societe_count; i++) {
                mergeCells.push({
                    row: 0,
                    col: i * 4,
                    rowspan: 1,
                    colspan: 4
                });
            }

            function findMinValue(rowData) {
                const numericValues = rowData
                    .filter((value, index) => index % 4 === 1)
                    .filter(value => !isNaN(value) && value !== 0 && value !== null && value !== '');
                return numericValues.length > 0 ? Math.min(...numericValues) : null;
            };
            // Initialiser Handsontable
            const hot = new Handsontable(container, {
                data: data,
                language: 'fr-FR', // Définir la langue sur français
                local: frFR,
                licenseKey: 'non-commercial-and-evaluation',
                rowHeaders: rowHeaders,
                rowHeaderWidth: rowHeaders.reduce((maxLength, header) => Math.max(maxLength, header.length),
                    0) * 10,
                colHeaders: colHeaders,
                columns: columns,
                mergeCells: mergeCells,
                manualColumnResize: true,
                manualRowResize: true,
                contextMenu: false,
                customBorders: borders,
                formulas: {
                    engine: HyperFormula,
                },
                preventOverflow: 'horizontal',
                colWidths(visualColumnIndex) {
                    if (visualColumnIndex % 4 === 0) {
                        return 115;
                    }
                    if (visualColumnIndex % 4 === 1) {
                        return 80;
                    }
                    if (visualColumnIndex % 4 === 2) {
                        return 50;
                    }
                    if (visualColumnIndex % 4 === 3) {
                        return 115;
                    }
                    return visualColumnIndex
                },
                autoColumnSize: {
                    syncLimit: '100%'
                },
                cells: function(row, col, prop) {
                    const cellProperties = {};
                    const data = this.instance.getData();
                    if (data[row][col] === 'UNDEFINED') {
                        cellProperties.readOnly = true; // Marquez la ligne comme readonly
                    }
                    if (row === 0) {
                        cellProperties.readOnly = true;
                        cellProperties.className += ' ht-center-first-row';
                    }
                    if (row == rowHeaders.length - 1) {
                        cellProperties.readOnly = true;

                    }
                    cellProperties.renderer = function(instance, td, row, col, prop, value) {
                        Handsontable.renderers.TextRenderer.apply(this, arguments);
                        const rowData = instance.getDataAtRow(
                            row); // Obtenir toutes les valeurs de la ligne
                        if (row == 0 || row == rowHeaders.length - 1) {
                            if (isDarkMode) {
                                td.style.backgroundColor = '#1e1e1f';
                                td.style.color = '#fff'; // Changer la couleur du texte
                            } else {
                                td.style.backgroundColor = '#f3f3f5';
                                td.style.color = '#000'; // Changer la couleur du texte
                            }
                        }
                        if (col % 4 === 1) {
                            const minValue = findMinValue(rowData); // Trouver la valeur minimale
                            if (value == minValue) {
                                td.style.backgroundColor = '#77DD77'; // Changer le fond en vert
                                td.style.color = '#145214'; // Changer la couleur du texte
                            }
                        }

                        if (data[row][col] === 'UNDEFINED') {
                            if (isDarkMode) {
                                td.style.backgroundColor = '#1e1e1f';
                            } else {
                                td.style.backgroundColor = '#f3f3f5';
                            }
                            td.style.color = 'rgba(0,0,0,0)'; // Changer la couleur du texte
                        }
                    };

                    return cellProperties;
                },


            });
            // applyGreenBackgroundOnLowestValue(hot);
            var firstdedit = false;

            function debounce(func, delay) {
                let timer;
                return function(...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), delay);
                };
            }

            // Appliquez le debounce à saveChanges
            const debouncedSaveChanges = debounce(saveChanges, 500);
            hot.addHook('afterChange', function(changes, source) {
                try {
                    if (source === 'loadData') return;
                    if (changes && firstdedit == true) {
                        debouncedSaveChanges();
                    }
                    firstdedit = true;
                } catch (error) {
                    console.error('Error in afterChange hook:', error);
                }
            });
            const exportPlugin = hot.getPlugin('exportFile');
            const button = document.querySelector('#refresh');
            button.addEventListener('click', () => {
                saveChanges();
            });

            function saveChanges() {
                const saveStatus0 = document.getElementById('save-status-0');
                const saveStatus1 = document.getElementById('save-status-1');
                const saveStatus2 = document.getElementById('save-status-2');
                saveStatus0.classList.remove('hidden');
                saveStatus1.classList.add('hidden');
                saveStatus2.classList.add('hidden');
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
                        body: JSON.stringify({
                            data: exportedString
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        saveStatus0.classList.add('hidden');
                        saveStatus1.classList.remove('hidden');
                        saveStatus2.classList.add('hidden');
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        saveStatus0.classList.add('hidden');
                        saveStatus1.classList.add('hidden');
                        saveStatus2.classList.remove('hidden');
                    });
            }
        });
    </script>



</x-app-layout>
