<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                {!! __('Créer une demande de prix') !!}
            </h2>
        </div>
    </x-slot>
    <div id="new-ddp" class="hidden">{{ $ddpid ? $ddpid : '' }}</div>
    <div class="py-4">
        <div class="max-w-8xl mx-auto sm:px-4 lg:px-6">
            <div class="shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100 px-2 grid grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <h1 class="text-xl font-semibold mb-4">Sélection des matières</h1>
                    <div class="flex gap-2">
                        <!-- Famille selection dropdown -->
                        <select name="famille" id="famille_id_search"
                            class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                            <option value="" selected>{!! __('Tous les types&nbsp;&nbsp;') !!}</option>
                            @foreach ($familles as $famille)
                                <option value="{{ $famille->id }}"
                                    {{ request('famille') == $famille->id ? 'selected' : '' }}>
                                    {!! $famille->nom . '&nbsp;&nbsp;' !!}
                                </option>
                            @endforeach
                        </select>
                        <!-- Sous-famille selection dropdown -->
                        <select name="sous_famille" id="sous_famille_id_search"
                            class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                            <option value="" selected>{!! __('Toutes les sous-familles &nbsp;&nbsp;') !!}</option>
                        </select>
                        <!-- Search bar for materials -->
                        <x-text-input placeholder="Recherchez une matière" id="searchbar" class="w-full" />
                    </div>
                    <div class="min-h-96 overflow-hidden bg-gray-100 dark:bg-gray-900 rounded">
                        <table>
                            <thead>
                                <th colspan="100">
                                    matières
                                </th>
                            </thead>
                            <tbody id="matiere-table">
                                <tr>
                                    <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                                        Recherchez une matière
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <h1 class="text-xl font-semibold">Demande de prix</h1>
                    <div class="w-full">
                        <x-input-label for="ddp-nom" value="Nom" />
                        <x-text-input label="Nom" name="ddp-nom" placeholder="Nom de la demande de prix" value="{{ isset($ddp) ? $ddp->nom : '' }}"
                            class="w-1/2" />
                    </div>
                    <div class="min-h-96 overflow-hidden bg-gray-100 dark:bg-gray-900 rounded">
                        <table>
                            <thead>
                                <th colspan="100">Matières sélectionnées</th>
                            </thead>
                            <tbody id="matiere-choisi-table">
                                @if ($ddp ?? false)
                                    @foreach ($ddp->ddpLigne as $ddp_ligne)
                                        <tr data-matiere-id="{{ $ddp_ligne->matiere->id }}" x-data
                                            data-fournisseurs-ids="{{ $ddp_ligne->fournisseurs->pluck('id')->join(';') }}"
                                            data-fournisseurs-noms="{{ $ddp_ligne->fournisseurs->pluck('raison_sociale')->join(';') }}"
                                            class="border-b border-gray-200 dark:border-gray-700 rounded-r-md overflow-hidden bg-white dark:bg-gray-800">
                                            <td class="text-left px-4">{{ $ddp_ligne->matiere->ref_interne }}</td>
                                            <td class="text-left px-4">{{ $ddp_ligne->matiere->designation }}</td>
                                            <td class="text-right px-4 flex items-center">
                                                <button type="button" class="btn-decrement px-2" onclick="decrementQuantity(this)">-</button>
                                                <x-text-input type="number" name="quantite[{{ $ddp_ligne->matiere->id }}]" class="w-20 text-right mx-2" value="{{ $ddp_ligne->quantite }}" min="1" />
                                                <button type="button" class="btn-increment px-2" onclick="incrementQuantity(this)">+</button>
                                            </td>
                                            <td class="text-right px-4">
                                                <button class="float-right" data-matiere-id="{{ $ddp_ligne->matiere->id }}" onclick="removeMatiere(event)">
                                                    <x-icons.close size="2" class="icons" />
                                                </button>
                                                <button class="float-right" data-matiere-id="{{ $ddp_ligne->matiere->id }}" onclick="showFournisseurs(event)"
                                                    x-on:click.prevent="$dispatch('open-modal', 'fournisseurs-modal')"
                                                    title="Fournisseurs">
                                                    <x-icons.view-object size="2" class="icons" />
                                                </button>
                                                <input type="hidden" name="fournisseur-{{ $ddp_ligne->matiere->id }}" value="{{ $ddp_ligne->fournisseurs->pluck('id')->join(';') }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr id="no-matiere">
                                        <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                                            Aucune matière sélectionnée
                                        </td>
                                    </tr>


                                @endif
                            </tbody>
                        </table>
                    </div>
                    <button class="bg-green-500 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700"
                            onclick="saveChanges()">
                            Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>
    <x-modal name="fournisseurs-modal" title="Fournisseurs" max-width="5xl">
        <div class="flex flex-col gap-4 p-4 text-gray-900 dark:text-gray-100">
            <h1 class="text-xl font-semibold">Fournisseurs</h1>
            <table class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                <thead>
                    <th>Nom</th>
                </thead>
                <tbody id="fournisseurs-table" class="">
                    <tr>
                        <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                            <div id="loading-spinner"
                                class=" mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 w-full">
                                <div
                                    class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32">
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
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>
                <h1 class="text-xl font-semibold">Autres Fournisseurs</h1>
                <div class="flex gap-2">
                    <x-text-input placeholder="Nom du fournisseur" class="m-4 w-1/2" id="searchbarFournisseur" />
                </div>
                <table class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                    <thead>
                        <th colspan="100">Nom</th>
                    </thead>
                    <tbody id="quicksearchfournisseurs-table" class="">
                        <tr>
                            <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                                Recherchez un fournisseur
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </x-modal>
    <script>
        // Function to update sous-familles based on selected famille
        function updateSousFamilles() {
            var familleId = document.getElementById('famille_id_search').value;
            var sousFamilleSelect = document.getElementById('sous_famille_id_search');

            // Clear previous options
            sousFamilleSelect.innerHTML =
                '<option value="" selected>Toutes les sous-familles &nbsp;&nbsp;</option>';

            if (familleId) {
                fetch(`/matieres/famille/${familleId}/sous-familles/json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(sousFamille => {
                            var option = document.createElement('option');
                            option.value = sousFamille.id;
                            option.textContent = sousFamille.nom;
                            sousFamilleSelect.appendChild(option);
                            var sousFamilleId = new URLSearchParams(window.location.search).get('sous_famille');

                            if (sousFamilleId) {
                                document.getElementById('sous_famille_id_search').value = sousFamilleId;
                            }
                        });
                        liveSearch();
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des sous familles :', error);
                    });
            }
        }

        function liveSearch() {
            const search = document.getElementById('searchbar').value;
            const familleId = document.getElementById('famille_id_search').value;
            const sousFamilleId = document.getElementById('sous_famille_id_search').value;
            const matiereTable = document.getElementById('matiere-table');

            fetch(
                    `/matieres/quickSearch?search=${encodeURIComponent(search)}&famille=${familleId}&sous_famille=${sousFamilleId}`
                    )
                .then(response => {
                    if (!response.ok) throw new Error('Erreur lors de la récupération des données');
                    return response.json();
                })
                .then(data => {
                    matiereTable.innerHTML = '';
                    if (data.matieres && data.matieres.length > 0) {
                        data.matieres.forEach(matiere => {
                            const tr = document.createElement('tr');
                            tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                                'rounded-r-md', 'overflow-hidden', 'bg-white', 'dark:bg-gray-800',
                                'cursor-pointer', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                            tr.setAttribute('data-matiere-id', matiere.id || '');
                            tr.setAttribute('data-matiere-ref', matiere.refInterne || '');
                            tr.setAttribute('data-matiere-designation', matiere.designation || '');
                            tr.addEventListener('click', addMatiere);
                            tr.innerHTML = `
                <td class="text-left px-4">${matiere.refInterne || '-'}</td>
                <td class="text-left px-4">${matiere.designation || '-'}</td>
                <td class="text-right px-4">${matiere.sousFamille || '-'}</td>
                    `;
                            matiereTable.appendChild(tr);
                        });
                    } else {
                        matiereTable.innerHTML =
                            '<tr><td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">Aucune matière trouvée</td></tr>';
                    }
                });
        }


        // Function to add selected material to the chosen list
        function addMatiere(event) {
            const matiereId = event.currentTarget.getAttribute('data-matiere-id');
            const matiereRef = event.currentTarget.getAttribute('data-matiere-ref');
            const matiereDesignation = event.currentTarget.getAttribute('data-matiere-designation');
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);

            if (existingRow) {
                const quantityInput = existingRow.querySelector('input[name^="quantite"]');
                quantityInput.value = parseInt(quantityInput.value) + 1;
            } else {
                const tr = document.createElement('tr');
                tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                    'rounded-r-md', 'overflow-hidden', 'bg-white', 'dark:bg-gray-800');
                tr.setAttribute('data-matiere-id', matiereId);
                tr.setAttribute('data-fournisseurs-ids', '');
                tr.setAttribute('data-fournisseurs-noms', '');
                tr.innerHTML = `
            <td class="text-left px-4">${matiereRef || '-'}</td>
            <td class="text-left px-4">${matiereDesignation || '-'}</td>
            <td class="text-right px-4 flex items-center">
                <button type="button" class="btn-decrement px-2" onclick="decrementQuantity(this)">-</button>
                <x-text-input type="number" name="quantite[${matiereId}]" class="w-20 text-right mx-2" value="1" min="1" />
                <button type="button" class="btn-increment px-2" onclick="incrementQuantity(this)">+</button>
            </td>
            <td class="text-right px-4">
                <button class=" float-right" data-matiere-id="${matiereId}" onclick="removeMatiere(event)">
                <x-icons.close size="2" class="icons" />
                </button>
                <button class=" float-right" data-matiere-id="${matiereId}" onclick="showFournisseurs(event)"
                x-on:click.prevent="$dispatch('open-modal', 'fournisseurs-modal')"
                title="Fournisseurs">
                <x-icons.view-object size="2" class="icons" />
                </button>
                <input type="hidden" name="fournisseur-${matiereId}" value="">
            </td>
            `;
                if (matiereChoisiTable.querySelector('#no-matiere')) {
                    matiereChoisiTable.innerHTML = '';
                }
                matiereChoisiTable.appendChild(tr);
            }
        }

        // Function to remove selected material from the chosen list
        function removeMatiere(event) {
            const matiereId = event.target.getAttribute('data-matiere-id');
            const row = event.target.closest('tr');
            row.remove();
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            if (matiereChoisiTable.querySelectorAll('tr').length === 0) {
                const tr = document.createElement('tr');
                tr.id = 'no-matiere';
                tr.innerHTML = `
                <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                    Aucune matière sélectionnée
                </td>
            `;
                matiereChoisiTable.appendChild(tr);
            }
        }

        // Function to increment the quantity of selected material
        function incrementQuantity(button) {
            const input = button.previousElementSibling;
            input.value = parseInt(input.value) + 1;
        }

        // Function to decrement the quantity of selected material
        function decrementQuantity(button) {
            const input = button.nextElementSibling;
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Function to show the list of suppliers for the selected material
        function showFournisseurs(event) {
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const matiereId = event.currentTarget.getAttribute('data-matiere-id');
            const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);
            const fournisseursIds = existingRow.getAttribute('data-fournisseurs-ids');
            const fournisseursNoms = existingRow.getAttribute('data-fournisseurs-noms');
            const fournisseursTable = document.getElementById('fournisseurs-table');
            if (fournisseursIds != "") {
                const fournisseurInput = document.querySelector(`input[name="fournisseur-${matiereId}"]`);
                const fournisseursSelecteds = fournisseurInput ? fournisseurInput.value : '';
                if (!fournisseurInput) {
                    console.error('Fournisseur input not found for matiere:', matiereId);
                    return;
                }
                fournisseursTable.innerHTML = '';
                fournisseursIds.split(';').forEach((fournisseurId, index) => {
                    const tr = document.createElement('tr');
                    const fournisseursSelected = fournisseursSelecteds.split(';').find(f => f == fournisseurId);
                    if (fournisseursSelected) {
                        tr.classList.add('bg-green-500', 'dark:bg-green-600', 'hover:bg-green-600',
                            'dark:hover:bg-green-700');
                    } else {
                        tr.classList.add('bg-white', 'dark:bg-gray-800', 'hover:bg-gray-200',
                            'dark:hover:bg-gray-700');
                    }
                    tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                        'rounded-r-md', 'overflow-hidden', 'cursor-pointer');
                    tr.setAttribute('data-fournisseur-id', fournisseurId);
                    tr.setAttribute('data-matiere-id', matiereId);
                    tr.addEventListener('click', addFournisseur);
                    tr.innerHTML = `
                    <td class="text-left px-4" colspan="2">${fournisseursNoms.split(';')[index] || '-'}</td>
                    `;
                    fournisseursTable.appendChild(tr);
                });
                return;
            }
            fournisseursTable.innerHTML =
                '<tr><td colspan="100"><div id="loading-spinner" class=" mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style></tr></td>';
            fetch(`/matieres/${matiereId}/fournisseurs/json`)
                .then(response => response.json())
                .then(data => {
                    fournisseursTable.innerHTML = '';
                    data.forEach(fournisseur => {
                        const tr = document.createElement('tr');
                        tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                            'rounded-r-md', 'overflow-hidden', 'bg-white', 'dark:bg-gray-800',
                            'cursor-pointer', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                        tr.setAttribute('data-fournisseur-id', fournisseur.id);
                        tr.setAttribute('data-matiere-id', matiereId);
                        tr.addEventListener('click', addFournisseur);
                        tr.innerHTML = `
                        <td class="text-left px-4" colspan="2">${fournisseur.raison_sociale || '-'}</td>
                        `;
                        fournisseursTable.appendChild(tr);
                        existingRow.setAttribute('data-fournisseurs-ids', data.map(f => f.id).join(';'));
                        existingRow.setAttribute('data-fournisseurs-noms', data.map(f => f.raison_sociale).join(
                            ';'));
                    });
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des fournisseurs :', error);
                });
        }

        // Function to add selected supplier to the material
        function addFournisseur(event) {
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const fournisseurId = event.currentTarget.getAttribute('data-fournisseur-id');
            let matiereId;
            if (event.currentTarget.getAttribute('data-is-from-quicksearch') == 'true') {
                matiereId = document.getElementById('fournisseurs-table').querySelector('tr:first-child').getAttribute(
                    'data-matiere-id');
            } else {
                matiereId = event.currentTarget.getAttribute('data-matiere-id');
            }
            const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);
            if (existingRow) {
                const fournisseurInput = existingRow.querySelector(`input[name="fournisseur-${matiereId}"]`);
                const fournisseursNoms = existingRow.getAttribute('data-fournisseurs-noms') || '';
                const currentFournisseurs = fournisseurInput.value ? fournisseurInput.value.split(';') : [];
                const index = currentFournisseurs.indexOf(fournisseurId);
                if (index === -1) {
                    // Add fournisseur
                    currentFournisseurs.push(fournisseurId);
                    event.currentTarget.classList.remove('bg-white', 'dark:bg-gray-800', 'hover:bg-gray-200',
                        'dark:hover:bg-gray-700');
                    event.currentTarget.classList.add('bg-green-500', 'dark:bg-green-600', 'hover:bg-green-600',
                        'dark:hover:bg-green-700');
                } else {
                    // Remove fournisseur
                    currentFournisseurs.splice(index, 1);
                    event.currentTarget.classList.remove('bg-green-500', 'dark:bg-green-600', 'hover:bg-green-600',
                        'dark:hover:bg-green-700');
                    event.currentTarget.classList.add('bg-white', 'dark:bg-gray-800', 'hover:bg-gray-200',
                        'dark:hover:bg-gray-700');
                }
                if (event.currentTarget.getAttribute('data-is-from-quicksearch') == 'true') {
                    const clonedRow = event.currentTarget.cloneNode(true);
                    const fournisseurNom = event.currentTarget.getAttribute('data-fournisseur-nom');
                    document.getElementById('fournisseurs-table').appendChild(clonedRow);
                    existingRow.setAttribute('data-fournisseurs-ids',
                        `${existingRow.getAttribute('data-fournisseurs-ids')};${fournisseurId}`);
                    existingRow.setAttribute('data-fournisseurs-noms',
                        `${existingRow.getAttribute('data-fournisseurs-noms')};${fournisseurNom}`);
                    event.currentTarget.remove();
                }
                fournisseurInput.value = currentFournisseurs.join(';');
            }
        }

        function saveChanges() {
            const ddpNom = document.querySelector('input[name="ddp-nom"]').value;
            const ddpId = document.getElementById('new-ddp').textContent;
            const matieres = [];
            document.querySelectorAll('#matiere-choisi-table tr[data-matiere-id]').forEach(row => {
                const matiereId = row.getAttribute('data-matiere-id');
                const quantity = row.querySelector(`input[name="quantite[${matiereId}]"]`).value;
                const fournisseurs = row.querySelector(`input[name="fournisseur-${matiereId}"]`).value;
                matieres.push({
                    id: matiereId,
                    quantity: quantity,
                    fournisseurs: fournisseurs.split(';')
                });
            });

            fetch('/ddp/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        ddp_id: ddpId,
                        nom: ddpNom,
                        matieres: matieres
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Demande de prix sauvegardée avec succès :', data);
                })
                .catch(error => {
                    console.error('Erreur lors de la sauvegarde de la demande de prix :', error);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Event listener for famille selection change
            document.getElementById('famille_id_search').addEventListener('change', function() {
                updateSousFamilles();
            });
            document.getElementById('sous_famille_id_search').addEventListener('change', function() {
                liveSearch();
            });
            const searchbar = document.getElementById('searchbar');
            const searchbarFournisseur = document.getElementById('searchbarFournisseur');
            const matiereTable = document.getElementById('matiere-table');

            // Event listener for search bar input

            searchbar.addEventListener('input', function() {
                liveSearch();
            });

            // Event listener for search bar input
            searchbarFournisseur.addEventListener('input', async (e) => {
                const search = e.target.value;
                if (search.length < 1) {
                    return;
                }
                const response = await fetch(
                    `/societes/fournisseurs/quickSearch?search=${encodeURIComponent(search)}`
                );
                const data = await response.json();
                const fournisseursTable = document.getElementById('quicksearchfournisseurs-table');
                fournisseursTable.innerHTML = '';
                if (data.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                        Aucun fournisseur trouvé
                    </td>
                `;
                    fournisseursTable.appendChild(tr);
                } else {

                    data.forEach(fournisseur => {
                        const tr = document.createElement('tr');
                        tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                            'rounded-r-md', 'overflow-hidden', 'cursor-pointer',
                            'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                        const fournisseurId = fournisseur.id || '';
                        tr.setAttribute('data-fournisseur-id', fournisseurId);
                        tr.setAttribute('data-fournisseur-nom', fournisseur.raison_sociale ||
                            '');
                        tr.setAttribute('data-is-from-quicksearch', 'true');
                        tr.addEventListener('click', addFournisseur);
                        tr.innerHTML = `
                        <td class="text-left px-4" colspan="2">${fournisseur.raison_sociale || '-'}</td>
                    `;
                        fournisseursTable.appendChild(tr);
                    });
                }
            });



        });
    </script>
</x-app-layout>
