<x-app-layout>
    @section('title', 'Retours - '.$cde->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('ddp_cde.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix et commandes</a>
                    >>
                    <a href="{{ route('cde.show', $cde->id) }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une demande de prix') !!}</a>
                    >> Retours
                </h2>

            </div>
            <a href="{{ route('cde.pdfs.download', $cde) }}" class="btn">Télécharger le PDF</a>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md" id="retour-container">
            <div class="flex justify-between items-center mb-6 flex-wrap ">
                <div class="flex items-center mb-12 flex-wrap ">
                    <h1 class="text-3xl font-bold  text-left mr-2">{{ $cde->nom }} - Récapitulatif</h1>
                    <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                        style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                        {{ $cde->statut->nom }}</div>
                </div>
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 flex items-center hidden"
                        title="Demande de prix en cours d'enregistrement" id="save-status-0">Enregistrement
                        en
                        cours...<x-icons.progress-activity size="2" /></h1>
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($cde) ? '' : 'hidden' }}"
                        title="Demande de prix enregistré avec succès" id="save-status-1">Enregistré</h1>
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($cde) ? 'hidden' : '' }}"
                        title="Demande de prix non enregistrée" id="save-status-2">Non-enregistré</h1>
                    <button class="" id="refresh">
                        <x-icons.refresh size="2" class="icons" />
                    </button>
                </div>
            </div>
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
            <div class="flex flex-col gap-4">
                <div class="">
                    <h1
                        class="text-xl font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-500 pb-2 mb-4 w-4/5">
                        Retour de commande</h1>
                    <div id="handsontable-container" class="ht-theme-main-dark-auto "></div>
                </div>
                <div class="">
                    <h1
                        class="text-xl font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-500 pb-2 mb-4">
                        Accusé de reception</h1>
                    @if ($cde->accuse_reception)
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col flex-wrap gap-4">
                            {{-- @dd($pdfs) --}}
                            @php
                                $pdf = $cde->accuse_reception;
                                $cdeannee = explode('-', $cde->code)[1];
                            @endphp
                            <button class="btn h-fit w-fit" onclick="changeAr(this)">
                                Changer
                            </button>
                            <div class="flex flex-col gap-2 bg-gray-100 dark:bg-gray-700 p-4 rounded-md hover:scale-105 cursor-pointer transition-all relative"
                                id="pdf" title="Ouvrir le PDF">
                                <h2
                                    class="text-xl font-semibold text-gray-700 dark:text-gray-200  border border-gray-300 dark:border-gray-700 pb-2 hover">
                                    {{ explode('_', $pdf)[count(explode('_', $pdf)) - 1] }}</h2>
                                <div style="background-color: rgba(0,0,0,0); height: 409px; width: 285px; margin-bottom: 15px;"
                                    class="absolute bottom-4"></div>
                                <object
                                    data="{{ route('cde.pdfshow', ['cde' => $cde, 'annee' => $cdeannee, 'nom' => $pdf]) }}"
                                    type="application/pdf" height="424px" width="300px">
                                    <p>Il semble que vous n'ayez pas de plugin PDF pour ce navigateur. Pas de
                                        problème... vous
                                        pouvez <a
                                            href="{{ route('cde.pdfshow', ['cde' => $cde, 'annee' => $cdeannee, 'nom' => $pdf]) }}">cliquer
                                            ici pour télécharger le fichier PDF.</a></p>
                                </object>
                            </div>
                        </div>

                    </div>
                        <div class="hidden m-4 h-[424px] w-[300px]" id="ar-parent">
                            <x-dropzone-input id="ar" name="ar" />
                        </div>
                    @else
                        <x-dropzone-input id="ar" name="ar" />
                    @endif

                </div>
                <div class="flex justify-between items-center mt-6  w-full">
                    <a href="{{ route('cde.cancel_validate', $cde->id) }}" class="btn float-right">Retour</a>
                    <a href="{{ route('cde.terminer', $cde->id) }}" class="btn float-right">Terminer</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function changeAr(button) {
            const ar = document.getElementById('ar-parent');
            ar.classList.toggle('hidden');
            document.getElementById('pdf').classList.toggle('hidden');
            button.textContent = ar.classList.contains('hidden') ? 'Changer' : 'Annuler';
        }
        document.addEventListener('DOMContentLoaded', function() {
            pdf = document.getElementById('pdf');
            if (pdf) {
                pdf.addEventListener('click', function() {
                const pdfUrl = pdf.querySelector('object').data;
                window.open(pdfUrl, '_blank');
            });}

            const inputAR = document.getElementById('ar');
            const url = '{{ route('cde.upload_ar', $cde) }}';
            inputAR.addEventListener('change', function(event) {
                const formData = new FormData();
                formData.append('accuse_reception', event.target.files[0]);
                document.getElementById('retour-container').innerHTML = `
                    <div id="loading-spinner"
                        class=" mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-dvh w-full">
                        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32">
                        </div>
                    </div>
                    <style>
                        .loader {
                            border-top-color: #3498db;
                            animation: spinner 1.5s linear infinite;
                        }

                        @keyframes spinner {
                            0% {
                                transform: rotate(0deg);
                            }

                            100% {
                                transform: rotate(360deg);
                            }
                        }
                    </style>
                `;
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            showFlashMessageFromJs('Erreur lors de l\'envoi du fichier', 2000, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFlashMessageFromJs('Erreur lors de l\'envoi du fichier', 2000, 'error');
                    });
            });


            const container = document.getElementById('handsontable-container');
            const rowHeaders = [
                @foreach ($cde->cdeLignes as $cdeLigne)
                    "<span title='{{ $cdeLigne->ref_interne }} {{ $cdeLigne->designation }}' class='text-xs'>{{ $cdeLigne->ref_interne }} {{ $cdeLigne->designation }}<span>",
                @endforeach
                'Total',
            ];

            const colHeaders = [
                'Statut',
                'Quantité',
                'PU HT',
                'Type d\'expédition',
                'Date livraison réelle',
            ];

            const mode_livraison = @json($typeExpedition);

            const columns = [{
                    type: 'select',
                    selectOptions: ['En cours', 'Annulée'],
                },
                {
                    type: 'numeric',
                },
                {
                    type: 'numeric',
                },
                {
                    type: 'select',
                    selectOptions: mode_livraison,
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
                            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                                'Septembre', 'Octobre', 'Novembre', 'Décembre'
                            ],
                            weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
                        }
                    },
                }
            ];

            const data = @json($data);

            const hot = new Handsontable(container, {
                data: data,
                language: 'fr-FR',
                licenseKey: 'non-commercial-and-evaluation',
                rowHeaders: rowHeaders,
                colHeaders: colHeaders,
                rowHeaderWidth: Math.min(
                    rowHeaders.reduce((maxLength, header) => Math.max(maxLength, header.length), 0) * 10,
                    Math.min(window.innerWidth * 0.4) // Limite maximale de largeur ajustée selon la taille de l'écran
                ),
                columns: columns,
                manualColumnResize: true,
                manualRowResize: true,
                contextMenu: false,
                preventOverflow: 'horizontal',
                autoColumnSize: false,
                colWidths: [100, 100, 100, 200, 200],
                cells: function(row, col, prop) {
                    var cellProperties = {};
                    datarows = this.instance.getData();
                    if (datarows[row][0] === 'Annulée' && col !== 0) {
                        cellProperties.readOnly = true;
                    }
                    return cellProperties;
                },
            });

            const debouncedSaveChanges = debounce(saveChanges, 500);
            hot.addHook('afterChange', function(changes, source) {
                if (source === 'loadData') return;
                if (changes) {
                    debouncedSaveChanges();
                }
            });

            document.querySelector('#refresh').addEventListener('click', saveChanges);

            function debounce(func, delay) {
                let timer;
                return function(...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), delay);
                };
            }

            function saveChanges() {
                const saveStatus0 = document.getElementById('save-status-0');
                const saveStatus1 = document.getElementById('save-status-1');
                const saveStatus2 = document.getElementById('save-status-2');
                saveStatus0.classList.remove('hidden');
                saveStatus1.classList.add('hidden');
                saveStatus2.classList.add('hidden');

                const exportPlugin = hot.getPlugin('exportFile');
                const exportedString = exportPlugin.exportAsString('csv', {
                    bom: false,
                    columnDelimiter: ',',
                    columnHeaders: false,
                    exportHiddenColumns: true,
                    exportHiddenRows: true,
                    rowDelimiter: '\r\n',
                    rowHeaders: false,
                });

                fetch('/cde/{{ $cde->id }}/save-retours', {
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
                        saveStatus0.classList.add('hidden');
                        saveStatus1.classList.remove('hidden');
                        saveStatus2.classList.add('hidden');
                    })
                    .catch((error) => {
                        saveStatus0.classList.add('hidden');
                        saveStatus1.classList.add('hidden');
                        saveStatus2.classList.remove('hidden');
                        console.error('Error:', error);
                    });
            }
        });
    </script>



</x-app-layout>
