<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                {!! __('Créer une commande') !!}
            </h2>
        </div>
    </x-slot>
    <div id="new-cde" class="hidden" x-data>{{ $cdeid ? $cdeid : '' }}</div>
    <div class="py-4">
        <div class="max-w-8xl mx-auto sm:px-4 lg:px-6">
            <div
                class="shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100 px-2 grid grid-cols-1 sm:grid-cols-2  gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <h1 class="text-xl font-semibold mb-2">Sélection des matières</h1>
                    <div class="flex flex-wrap gap-2">
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
                    <div class="min-h-96 overflow-x-auto bg-gray-100 dark:bg-gray-900 rounded">
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
                    <form class="bg-white dark:bg-gray-800 flex flex-col gap-4 rounded-md">
                        @csrf
                        <input type="hidden" name="cde_id" value="{{ $cde->id ?? '' }}">
                        <div class="flex justify-between items-center">
                            <h1 class="text-xl font-semibold">Commande</h1>
                            <div class="flex items-center">
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 flex items-center hidden"
                                    title="Demande de prix en cours d'enregistrement" id="save-status-0">Enregistrement
                                    en
                                    cours...<x-icons.progress-activity size="2" /></h1>
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($cde) ? '' : 'hidden' }}"
                                    title="Demande de prix enregistré avec succès" id="save-status-1">Enregistré</h1>
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($cde) ? 'hidden' : '' }}"
                                    title="Demande de prix non enregistrée" id="save-status-2">Non-enregistré</h1>
                                <button class="" onclick="saveChanges()" type="button">
                                    <x-icons.refresh size="2" class="icons" />
                                </button>
                            </div>
                        </div>
                        <div class="w-full flex gap-4">
                            <div class="w-auto">
                                <x-input-label for="cde-entite" value="Pour" />
                                <select name="cde-entite" id="cde-entite"
                                    class="select w-auto {{ isset($cde) && $cde->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }} p-3">
                                    @foreach ($entites as $entite)
                                        <option value="{{ $entite->id }}"
                                            {{ isset($cde) && $cde->entite_id == $entite->id ? 'selected' : '' }}>
                                            {{ $entite->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-2/3">
                                <x-input-label for="cde-nom" value="Nom" />
                                <x-text-input label="Nom" name="cde-nom" id="cde-nom"
                                    placeholder="Nom de la demande de prix" autofocus
                                    value="{{ isset($cde) && $cde->nom != 'undefined' ? $cde->nom : '' }}"
                                    class="w-1/2 {{ isset($cde) && $cde->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }}" />
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <div>
                                <x-input-label for="societe_select" value="Fournisseur" />
                                <select name="societe_select" id="societe_select" class="select w-auto"
                                    onchange="selectSociete()">
                                    @if (!$cde->societe)
                                        <option value="" selected disabled>Choisir une société</option>
                                    @endif
                                    @foreach ($societes as $societe)
                                        <option value="{{ $societe->id }}"
                                            {{ isset($cde->societe) && $cde->societe->id == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->raison_sociale }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-auto">
                                <select name="etablissement_select" id="etablissement_select"
                                    class="select w-auto {{ isset($cde->etablissement) ? '' : 'hidden' }}"
                                    onchange="etablissementSelect()">
                                    @if ($cde->etablissement)
                                        @foreach ($cde->societe->etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}"
                                                {{ isset($cde->etablissement) && $cde->etablissement->id == $etablissement->id ? 'selected' : '' }}>
                                                {{ $etablissement->nom }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mt-auto">
                                <select name="societe_contact_select" id="societe_contact_select"
                                    class="select w-auto {{ isset($cde->societe_contact) ? '' : 'hidden' }}"
                                    onchange="saveChanges(); document.getElementById('searchbar').focus();liveSearch()">
                                    @if ($cde->societe_contact)
                                        @foreach ($cde->societe->contacts as $contact)
                                            <option value="{{ $contact->id }}"
                                                {{ isset($cde->societe_contact) && $cde->societe_contact->id == $contact->id ? 'selected' : '' }}>
                                                {{ $contact->nom }} {{ $contact->prenom }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>
                        <div class="mt-auto">
                            <x-toggle name="show_ref_fournisseur" id="show_ref_fournisseur"
                                label="Afficher les références fournisseur" />
                        </div>
                        <div class="min-h-96 overflow-x-auto bg-gray-100 dark:bg-gray-900 rounded">
                            <table>
                                <thead>
                                    <th colspan="100" class="border-r-4 border-gray-50 dark:border-gray-800">
                                        Matières
                                        sélectionnées</th>

                                </thead>
                                <tbody id="matiere-choisi-table">
                                    @if ($cde && $cde->cdeLignes->count() > 0)
                                        @foreach ($cde->cdeLignes as $cde_ligne)
                                            <tr data-matiere-id="{{ $cde_ligne->matiere->id }}"
                                                data-fournisseurs-ids="{{ $cde_ligne->fournisseurs->pluck('id')->join(';') }}"
                                                data-fournisseurs-noms="{{ $cde_ligne->fournisseurs->pluck('raison_sociale')->join(';') }}"
                                                class="border-b border-gray-200 dark:border-gray-700 rounded-r-md overflow-hidden bg-white dark:bg-gray-800 border-r-4 border-r-green-500 dark:border-r-green-600">
                                                <td class="text-left px-4">{{ $cde_ligne->matiere->ref_interne }}
                                                </td>
                                                <td class="text-left px-4">{{ $cde_ligne->matiere->designation }}
                                                </td>
                                                <td class="text-right px-4 flex items-center"
                                                    title="{{ $cde_ligne->matiere->unite->full }}">
                                                    <button type="button" class="btn-decrement px-2"
                                                        onclick="decrementQuantity(this)">-</button>
                                                    <x-text-input type="number"
                                                        name="quantite[{{ $cde_ligne->matiere->id }}]"
                                                        oninput="saveChanges()" class="w-20 text-right mx-2"
                                                        value="{{ $cde_ligne->quantite }}" min="1" />
                                                    {{ $cde_ligne->matiere->unite->short }}
                                                    <button type="button" class="btn-increment px-2"
                                                        onclick="incrementQuantity(this)">+</button>
                                                </td>
                                                <td class="text-right px-4">
                                                    <button class="float-right"
                                                        data-matiere-id="{{ $cde_ligne->matiere->id }}"
                                                        onclick="removeMatiere(event)">
                                                        <x-icons.close size="2" class="icons" />
                                                    </button>
                                                    <button class="float-right"
                                                        data-matiere-id="{{ $cde_ligne->matiere->id }}"
                                                        onclick="showFournisseurs(event)"
                                                        x-on:click.prevent="$dispatch('open-modal', 'fournisseurs-modal')"
                                                        title="Fournisseurs">
                                                        <x-icons.list size="2" class="icons" />
                                                    </button>
                                                    <input type="hidden"
                                                        name="fournisseur-{{ $cde_ligne->matiere->id }}"
                                                        value="{{ $cde_ligne->fournisseurs->pluck('id')->join(';') }}">
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
                    </form>
                    <div class="flex justify-between gap-4">
                        <div>
                            <button class="bg-red-500 hover:bg-red-600 btn"
                                onclick="event.preventDefault(); document.getElementById('confirm-delete-modal').classList.remove('hidden');">Supprimer</button>
                            <button class="btn" type="button"
                                x-on:click="$dispatch('open-modal', 'reset-commande-modal')">Réinitialiser</button>
                        </div>
                        <x-modal name="reset-commande-modal" title="Réinitialiser" max-width="5xl">
                            <div class="flex flex-col gap-4 p-4 text-gray-900 dark:text-gray-100">
                                <div class="flex justify-between items-center">
                                    <h1 class="text-xl font-semibold">Réinitialiser la commande</h1>
                                    <a x-on:click="$dispatch('close')">
                                        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                                    </a>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">Voulez-vous vraiment réinitialiser la
                                    commande ?</p>
                                <div class="flex justify-end gap-4">
                                    <button class=" text-white px-4 py-2 rounded btn"
                                        x-on:click="$dispatch('close')">Annuler</button>
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                                        onclick="window.location.href = '{{ route('cde.create') }}'">Réinitialiser</button>
                                </div>
                            </div>
                        </x-modal>
                        <div id="confirm-delete-modal"
                            class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                                <h2 class="text-xl font-semibold mb-4">Voulez-vous vraiment supprimer ?</h2>
                                <p class="mb-4">Cette action est irréversible.</p>
                                <div class="flex justify-end gap-4">
                                    <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
                                        onclick="document.getElementById('confirm-delete-modal').classList.add('hidden');">Annuler</button>
                                    <form action="{{ route('cde.destroy', ['cde' => $cdeid]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <button class=" btn"
                            onclick="if (document.getElementById('cde-nom').value.trim() != '') { window.location.href = '{{ route('cde.validation', ['cde' => $cdeid]) }}'; } else { alert('Veuillez renseigner le nom de la demande de prix'); }">Suivant</button>
                    </div>
                </div>
            </div>
        </div>
    </div>







    <x-modal name="fournisseurs-modal" title="Fournisseurs" max-width="5xl">
        <div class="flex flex-col gap-4 p-4 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold">Fournisseurs</h1>
                <a x-on:click="$dispatch('close')">
                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                </a>
            </div>
            <table class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                <thead>
                    <th class="text-sm">Nom</th>
                    <th>
                        <button class="float-right" onclick="showFournisseurs(event,1)">
                            <x-icons.refresh size="2" class="icons" />
                        </button>
                    </th>
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
                <div class="flex gap-2 m-2">
                    <x-text-input placeholder="Nom du fournisseur" class=" w-1/2" id="searchbarFournisseur" />
                    <button class="btn" onclick="liveSearchFournisseurs()">Rechercher</button>
                </div>
                <table class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                    <thead>
                        <th colspan="100" class="text-sm">Nom</th>
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
        const unites = @json($unites);

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

        function selectSociete() {
            var societeId = document.getElementById('societe_select').value;
            var etablissementSelectDOM = document.getElementById('etablissement_select');
            const societeContactSelect = document.getElementById('societe_contact_select');
            etablissementSelectDOM.innerHTML = '';
            societeContactSelect.innerHTML = '';
            fetch(`/societe/${societeId}/etablissements/json`)
                .then(response => response.json())
                .then(data => {
                    if (data.length == 0) {
                        var option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Aucun établissement trouvé';
                        option.disabled = true;
                        etablissementSelectDOM.appendChild(option);
                        return;
                    } else if (data.length == 1) {
                        var option = document.createElement('option');
                        option.value = data[0].id;
                        option.textContent = data[0].nom;
                        option.selected = true;
                        etablissementSelectDOM.appendChild(option);
                        etablissementSelectDOM.classList.remove('hidden');
                        etablissementSelectDOM.dispatchEvent(new Event('change'));
                        etablissementSelect();
                        return;
                    }
                    var option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Choisir un établissement';
                    option.selected = true;
                    option.disabled = true;
                    etablissementSelectDOM.appendChild(option);
                    data.forEach(etablissement => {
                        var option = document.createElement('option');
                        option.value = etablissement.id;
                        option.textContent = etablissement.nom;
                        etablissementSelectDOM.appendChild(option);
                        etablissementSelectDOM.classList.remove('hidden');
                    });

                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des établissements :', error);
                });
        }

        function etablissementSelect() {
            var etablissementId = document.getElementById('etablissement_select').value;
            var societeContactSelect = document.getElementById('societe_contact_select');
            var societeId = document.getElementById('societe_select').value;
            societeContactSelect.innerHTML = '';
            fetch(`/societes/${societeId}/etablissements/${etablissementId}/contacts/json`)
                .then(response => response.json())
                .then(data => {
                    societeContactSelect.innerHTML = '';
                    if (data.length == 0) {
                        var option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Aucun contact trouvé';
                        option.disabled = true;
                        societeContactSelect.appendChild(option);
                        return;
                    } else if (data.length == 1) {
                        var option = document.createElement('option');
                        option.value = data[0].id;
                        option.textContent = data[0].nom + ' ' + data[0].email;
                        option.selected = true;
                        societeContactSelect.appendChild(option);
                        societeContactSelect.classList.remove('hidden');
                        saveChanges();
                        document.getElementById('searchbar').focus();
                        liveSearch();
                        return;
                    }
                    var option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Choisir un contact';
                    option.selected = true;
                    option.disabled = true;
                    societeContactSelect.appendChild(option);
                    data.forEach(contact => {
                        var option = document.createElement('option');
                        option.value = contact.id;
                        option.textContent = contact.nom + ' ' + contact.email;
                        societeContactSelect.appendChild(option);
                        societeContactSelect.classList.remove('hidden');
                    });

                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des contacts :', error);
                });
        }

        function liveSearch() {
            const searchbar = document.getElementById('searchbar');
            const search = searchbar.value;
            const familleId = document.getElementById('famille_id_search').value;
            const sousFamilleId = document.getElementById('sous_famille_id_search').value;
            const matiereTable = document.getElementById('matiere-table');
            const societeContactSelect = document.getElementById('societe_contact_select');
            if (societeContactSelect.value == "") {
                searchbar.classList.add('border-red-500', 'dark:border-red-600', 'border-2', 'focus:border-red-500',
                    'focus:dark:border-red-600');
                showFlashMessageFromJs('Veuillez d\'abord sélectionner un destinataire', 2000, 'error');
                searchbar.blur();
                document.getElementById('societe_select').focus();
                return;
            }
            const societe_select = document.getElementById('societe_select');
            fetch(
                    `/matieres/quickSearch?search=${encodeURIComponent(search)}&famille=${familleId}&sous_famille=${sousFamilleId}&with_last_price=1&societe=${societe_select.value}`
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
                            tr.setAttribute('data-prix', matiere.lastPrice || '');
                            tr.setAttribute('data-matiere-basic-unite', matiere.Unite || '');
                            tr.setAttribute('data-matiere-unite', matiere.lastPriceUnite || '');
                            tr.addEventListener('click', addMatiere);
                            if (matiere.lastPrice && matiere.lastPriceUnite) {
                                tr.innerHTML = `
                                    <td class="text-left px-4">${matiere.refInterne || '-'}</td>
                                    <td class="text-left px-4">${matiere.designation || '-'}</td>
                                    <td class="text-right px-4 font-bold"> ${matiere.lastPrice + ' €/' + matiere.lastPriceUnite} </td>
                                    <td class="text-left px-4">${matiere.lastPriceDate || '-'}</td>
                                    <td class="text-right px-4">${matiere.sousFamille || '-'}</td>
                                        `;
                            } else {
                                tr.innerHTML = `
                                    <td class="text-left px-4">${matiere.refInterne || '-'}</td>
                                    <td class="text-left px-4">${matiere.designation || '-'}</td>
                                    <td class="text-center pr-6" colspan="2"> Aucun prix </td>
                                    <td class="text-right px-4">${matiere.sousFamille || '-'}</td>
                                        `;
                            }
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
            var matiereRef = event.currentTarget.getAttribute('data-matiere-ref');
            const matiereBasicUnite = event.currentTarget.getAttribute('data-matiere-basic-unite');
            const matiereUnite = event.currentTarget.getAttribute('data-matiere-unite');
            const matiereDesignation = event.currentTarget.getAttribute('data-matiere-designation');
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const matierePrix = event.currentTarget.getAttribute('data-prix');
            const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);
            const selectSociete = document.getElementById('societe_select');
            const showRefFournisseur = document.getElementById('show_ref_fournisseur');

            if (existingRow) {
                const quantityInput = existingRow.querySelector('input[name^="quantite"]');
                quantityInput.value = parseInt(quantityInput.value) + 1;
            } else {
                const tr = document.createElement('tr');
                tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                    'rounded-r-md', 'overflow-hidden', 'bg-white', 'dark:bg-gray-800', 'border-r-4', 'text-sm');
                tr.setAttribute('data-matiere-id', matiereId);
                tr.setAttribute('data-fournisseurs-ids', '');
                tr.setAttribute('data-fournisseurs-noms', '');
                if (showRefFournisseur.checked == true) {
                    matiereRef = `
                    <div class="flex flex-col">

                        <div class="flex flex-col">
                            <span class="text-xs">Réf. Interne</span>
                            <span class="font-bold">${matiereRef || '-'}</span>
                            <input type="hidden" name="ref_interne[${matiereId}]" value="${matiereRef || ''}">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs">Réf. Fournisseur</span>
                            <x-text-input name="ref_fournisseur[${matiereId}]" value="${matiereRef || ''}" class="font-bold p-0 border-0 bg-white dark:bg-gray-700 w-auto" />
                            </div>
                    </div>
                    `;
                }
                    tr.innerHTML = `
    <td class="text-left px-2 sm:px-4 py-2">${matiereRef || '-'}</td>
    <td class="text-left px-2 sm:px-4 py-2">${matiereDesignation || '-'}</td>
    <td class="text-right py-2">
        <div class="flex items-center justify-end">
            <!-- Boutons de quantité -->
            <button type="button" class="btn-decrement btn-select-left w-6 px-0" onclick="decrementQuantity(this)">
                -
            </button>
            <x-text-input
                type="number"
                name="quantite[${matiereId}]"
                class="w-16 text-right mx-1"
                value="1"
                min="1"
                oninput="saveChanges()"
            />
            <button type="button" class="btn-increment btn-select-right w-6 px-0" onclick="incrementQuantity(this)">
                +
            </button>

            <!-- Sélecteur d'unité -->
            <select
                name="unite[${matiereId}]"
                class="w-16 mx-2 select"
                onchange="saveChanges()"
            >
                ${unites.map(unite => `
                                    <option
                                        value="${unite.id}" title="${unite.full}"
                                        ${unite.short === matiereUnite ? 'selected' : matiereBasicUnite === unite.short ? 'selected' : ''}
                                    >
                                        ${unite.short}
                                    </option>
                                `).join('')}
            </select>

            <!-- Champ de date -->
            <x-date-input
                name="date[${matiereId}]"
                class="w-fit"
                value=""
                oninput="saveChanges()"
            />
        </div>
    </td>
    <td class="text-left py-2">
        <div class="price-input-container flex items-center">
            <x-text-input
                type="number"
                name="prix[${matiereId}]"
                class="price-input"
                value="${matierePrix || '1'}"
                min="0"
                step="0.01"
                oninput="saveChanges()"
            />
        </div>
    </td>
    <td class="text-right py-2">
        <button
            class="float-right"
            data-matiere-id="${matiereId}"
            onclick="removeMatiere(event)"
        >
            <x-icons.close size="2" class="icons" />
        </button>
    </td>
`;
                    if (matiereChoisiTable.querySelector('#no-matiere')) {
                        matiereChoisiTable.innerHTML = '';
                    }
                    matiereChoisiTable.appendChild(tr);
                    selectSociete.disabled = true;

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
                    document.getElementById('societe_select').disabled = false;
                }
                saveChanges();
            }

            // Function to increment the quantity of selected material
            function incrementQuantity(button) {
                const input = button.previousElementSibling;
                input.value = parseInt(input.value) + 1;
                saveChanges();
            }

            // Function to decrement the quantity of selected material
            function decrementQuantity(button) {
                const input = button.nextElementSibling;
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    saveChanges();
                }
            }

            // Function to show the list of suppliers for the selected material
            function showFournisseurs(event, isRefresh = 0) {
                let matiereId = "";
                const fournisseursTable = document.getElementById('fournisseurs-table');

                if (isRefresh == 1) {
                    matiereId = fournisseursTable.querySelector('tr:first-child').getAttribute('data-matiere-id');
                } else {
                    matiereId = event.currentTarget.getAttribute('data-matiere-id');
                }
                const matiereChoisiTable = document.getElementById('matiere-choisi-table');
                const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);
                const fournisseursIds = existingRow.getAttribute('data-fournisseurs-ids');
                const fournisseursNoms = existingRow.getAttribute('data-fournisseurs-noms');
                const fournisseurInput = document.querySelector(`input[name="fournisseur-${matiereId}"]`);
                if (fournisseursIds != "" && isRefresh == 0) {
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
                        let FinalDataIds = [];
                        let FinalDataNoms = [];
                        data.forEach(fournisseur => {
                            FinalDataIds.push(fournisseur.id.toString());
                            FinalDataNoms.push(fournisseur.raison_sociale);
                        });
                        if (fournisseurInput.value != "") {
                            fournisseurInput.value.split(';').forEach(id => {
                                if (!FinalDataIds.includes(id)) {
                                    FinalDataIds.push(id);
                                    let index = existingRow.getAttribute('data-fournisseurs-ids').split(';')
                                        .indexOf(id.toString());
                                    FinalDataNoms.push(existingRow.getAttribute('data-fournisseurs-noms').split(
                                        ';')[index]);
                                }
                            });
                        }
                        FinalDataIds = [...new Set(FinalDataIds)];
                        FinalDataNoms = [...new Set(FinalDataNoms)];
                        FinalDataIds.forEach((fournisseurId, index) => {
                            const tr = document.createElement('tr');
                            const fournisseursSelected = fournisseurInput.value.split(';').find(f => f ==
                                fournisseurId);
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
                        <td class="text-left px-4" colspan="2">${FinalDataNoms[index] || '-'}</td>
                        `;
                            fournisseursTable.appendChild(tr);
                        });
                        existingRow.setAttribute('data-fournisseurs-ids', FinalDataIds.join(';'));
                        existingRow.setAttribute('data-fournisseurs-noms', FinalDataNoms.join(';'));
                        liveSearchFournisseurs();
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
                        var fournisseurtotal = Array.from(new Set(Array.from(document.querySelectorAll(
                            `input[name^="fournisseur-"]`)).map(input => input.value.split(';')).flat().concat(
                            fournisseurId))).length;
                        if (fournisseurtotal > 10) {
                            showFlashMessageFromJs('Vous ne pouvez pas ajouter plus de 10 fournisseurs différents', duree =
                                2000, type = 'error')
                            return;
                        }
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
                        const fournisseurNom = event.currentTarget.getAttribute('data-fournisseur-nom');
                        const existingFournisseur = document.querySelector(
                            `#fournisseurs-table tr[data-fournisseur-id="${fournisseurId}"]`);
                        if (!existingFournisseur) {
                            const clonedRow = event.currentTarget.cloneNode(true);
                            document.getElementById('fournisseurs-table').appendChild(clonedRow);
                            existingRow.setAttribute('data-fournisseurs-ids',
                                `${existingRow.getAttribute('data-fournisseurs-ids')};${fournisseurId}`);
                            existingRow.setAttribute('data-fournisseurs-noms',
                                `${existingRow.getAttribute('data-fournisseurs-noms')};${fournisseurNom}`);
                            event.currentTarget.remove();
                        }
                    }

                    fournisseurInput.value = currentFournisseurs.join(';');
                    saveChanges();
                }
            }

            function saveChanges() {
                const cdeEntite = document.getElementById('cde-entite');
                const cdeNom = document.querySelector('input[name="cde-nom"]');
                const cdeId = document.getElementById('new-cde').textContent.trim();
                const saveStatus0 = document.getElementById('save-status-0');
                const saveStatus1 = document.getElementById('save-status-1');
                const saveStatus2 = document.getElementById('save-status-2');
                const matiereChoisiTable = document.getElementById('matiere-choisi-table');
                const searchbar = document.getElementById('searchbar');
                searchbar.classList.remove('border-red-500', 'dark:border-red-600', 'border-2', 'focus:border-red-500',
                    'focus:dark:border-red-600');
                saveStatus0.classList.remove('hidden');
                saveStatus1.classList.add('hidden');
                saveStatus2.classList.add('hidden');
                if ('' === cdeNom.value.trim()) {
                    saveStatus0.classList.add('hidden');
                    saveStatus2.classList.remove('hidden');
                    return;
                }
                if (cdeId === '') {
                    saveStatus0.classList.add('hidden');
                    saveStatus2.classList.remove('hidden');
                    return;
                }
                if (!matiereChoisiTable.querySelector('tr[data-matiere-id]')) {

                    saveStatus0.classList.add('hidden');
                    saveStatus2.classList.remove('hidden');
                    return;
                }
                if (matiereChoisiTable.querySelector('tr[data-matiere-id] input[name^="fournisseur-"]').value === '') {
                    saveStatus0.classList.add('hidden');
                    saveStatus2.classList.remove('hidden');
                    return;
                }
                const matieres = [];
                document.querySelectorAll('#matiere-choisi-table tr[data-matiere-id]').forEach(row => {
                    const matiereId = row.getAttribute('data-matiere-id');
                    const quantity = row.querySelector(`input[name="quantite[${matiereId}]"]`).value;
                    const fournisseurs = row.querySelector(`input[name="fournisseur-${matiereId}"]`).value;
                    row.classList.remove('border-r-green-500', 'dark:border-r-green-600');
                    if (quantity < 1) {
                        saveStatus0.classList.add('hidden');
                        saveStatus2.classList.remove('hidden');
                        return;
                    }
                    if (fournisseurs !== '') {
                        matieres.push({
                            id: matiereId,
                            quantity: quantity,
                            fournisseurs: fournisseurs.split(';')
                        });
                        row.classList.add('border-r-green-500', 'dark:border-r-green-600');
                        cdeNom.classList.add('border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                        cdeEntite.classList.add('border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                    }
                });
                fetch('/cde/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            cde_id: cdeId,
                            entite_id: cdeEntite.value,
                            nom: cdeNom.value,
                            matieres: matieres
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        saveStatus0.classList.add('hidden');
                        saveStatus1.classList.remove('hidden');
                    })
                    .catch(error => {
                        saveStatus0.classList.add('hidden');
                        saveStatus2.classList.remove('hidden');
                    });
            }

            async function liveSearchFournisseurs() {
                const search = document.getElementById('searchbarFournisseur').value;
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
                        tr.setAttribute('data-fournisseur-nom', fournisseur.raison_sociale || '');
                        tr.setAttribute('data-is-from-quicksearch', 'true');
                        tr.addEventListener('click', addFournisseur);
                        tr.innerHTML = `
                        <td class="text-left px-4" colspan="2">${fournisseur.raison_sociale || '-'}</td>
                    `;
                        fournisseursTable.appendChild(tr);
                    });
                }
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
                const cdeEntite = document.getElementById('cde-entite');
                const cdeNom = document.getElementById('cde-nom');

                // Event listener for search bar input

                searchbar.addEventListener('input', function() {
                    liveSearch();
                });
                searchbarFournisseur.addEventListener('input', function() {
                    liveSearchFournisseurs();
                });



                cdeNom.addEventListener('input', function() {
                    if (cdeNom.value !== undefined && cdeNom.value.trim() !== '') {
                        saveChanges();
                    }
                });
                cdeEntite.addEventListener('change', function() {
                    saveChanges();
                });

            });
            // Add CSS for euro symbol
    </script>
</x-app-layout>
