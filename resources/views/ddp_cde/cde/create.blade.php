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
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Pour Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
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
                                    class="select w-48 {{ isset($cde) && $cde->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }} pt-3 pb-3">
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
                                    placeholder="Nom de la commande" autofocus
                                    value="{{ isset($cde) && $cde->nom != 'undefined' ? $cde->nom : '' }}"
                                    class="min-w-full {{ isset($cde) && $cde->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }}" />
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <div>
                                <x-input-label for="societe_select" value="Fournisseur" />
                                <select name="societe_select" id="societe_select"
                                    class="select w-48 {{ isset($cde->societe_contact_id) ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }}"
                                    onchange="selectSociete()">
                                    @if ($cde->societe_contact_id)
                                        <option value="" selected disabled>Choisir une société</option>
                                    @endif
                                    @foreach ($societes as $societe)
                                        <option value="{{ $societe->id }}"
                                            {{ isset($cde->societe_contact_id) && $cde->societe->id == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->raison_sociale }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-auto">
                                <select name="etablissement_select" id="etablissement_select"
                                    class="select w-auto {{ isset($cde->societe_contact_id) ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : 'hidden' }}"
                                    onchange="etablissementSelect()">
                                    @if ($cde->societe_contact_id)
                                        @foreach ($cde->societe->etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}"
                                                {{ isset($cde->societe_contact_id) && $cde->etablissement->id == $etablissement->id ? 'selected' : '' }}>
                                                {{ $etablissement->nom }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mt-auto">
                                <select name="societe_contact_select" id="societe_contact_select"
                                    class="select w-auto {{ isset($cde->societe_contact_id) ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : 'hidden' }}"
                                    onchange="saveChanges(); document.getElementById('searchbar').focus();liveSearch()">
                                    @if ($cde->societe_contact_id)
                                        @foreach ($cde->societe->societeContacts as $contact)
                                            <option value="{{ $contact->id }}"
                                                {{ isset($cde->societe_contact_id) && $cde->societeContact->id == $contact->id ? 'selected' : '' }}>
                                                {{ $contact->nom }} {{ $contact->prenom }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>
                        <div class="mt-auto">
                            <x-toggle name="show_ref_fournisseur" id="show_ref_fournisseur" :checked="$showRefFournisseur"
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
                                            <tr class="border-b border-gray-200 dark:border-gray-700 rounded-r-md overflow-hidden bg-white dark:bg-gray-800 border-r-green-500 dark:border-r-green-600 border-r-4 text-sm"
                                                data-matiere-id="{{ $cde_ligne->matiere_id }}">
                                                <td class="text-left ml-1">
                                                    <div class="flex flex-col {{ $showRefFournisseur ? '' : 'hidden' }}"
                                                        id="refs-{{ $cde_ligne->matiere_id }}">
                                                        <div class="flex flex-col">
                                                            <span class="text-xs">Réf. Interne</span>
                                                            <span
                                                                class="font-bold">{{ $cde_ligne->ref_interne ?? '-' }}</span>
                                                            <input type="hidden"
                                                                name="ref_interne[{{ $cde_ligne->matiere_id }}]"
                                                                value="{{ $cde_ligne->ref_interne ?? '' }}">
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <span class="text-xs">Réf. Fournisseur</span>
                                                            <x-text-input
                                                                name="ref_fournisseur[{{ $cde_ligne->matiere_id }}]"
                                                                value="{{ $cde_ligne->ref_fournisseur ?? '' }}"
                                                                class="font-bold p-0 border-0 bg-white dark:bg-gray-700 max-w-24"
                                                                onblur="saveChanges()" />
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col {{ $showRefFournisseur ? 'hidden' : '' }}"
                                                        id="ref-{{ $cde_ligne->matiere_id }}">
                                                        <div class="flex flex-col">
                                                            <span class="text-xs">Réf. Interne</span>
                                                            <span
                                                                class="font-bold">{{ $cde_ligne->ref_interne ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-left py-2">
                                                    {{ $cde_ligne->designation ?? '-' }}
                                                    <input type="hidden"
                                                        name="designation[{{ $cde_ligne->matiere_id }}]"
                                                        value="{{ $cde_ligne->designation ?? '' }}">
                                                </td>
                                                <td class="text-right py-2">
                                                    <div class="flex items-center justify-end">
                                                        <x-text-input type="number"
                                                            name="quantite[{{ $cde_ligne->matiere_id }}]"
                                                            class="w-24"
                                                            value="{{ rtrim(rtrim($cde_ligne->quantite, '0'), '.') }}"
                                                            min="0" oninput="saveChanges()" />
                                                        <select name="unite[{{ $cde_ligne->matiere_id }}]"
                                                            class="w-16 mx-2 select" onchange="saveChanges()">
                                                            @foreach ($unites as $unite)
                                                                <option value="{{ $unite->id }}"
                                                                    title="{{ $unite->full }}"
                                                                    {{ $unite->id === $cde_ligne->unite_id ? 'selected' : '' }}>
                                                                    {{ $unite->short }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-date-input name="date[{{ $cde_ligne->matiere_id }}]"
                                                            class="w-fit" value="{{ $cde_ligne->date_livraison }}"
                                                            oninput="saveChanges()" />
                                                    </div>
                                                </td>
                                                <td class="text-left py-2">
                                                    <div class="price-input-container flex items-center">
                                                        <x-text-input type="number"
                                                            name="prix[{{ $cde_ligne->matiere_id }}]"
                                                            class="price-input"
                                                            value="{{ $cde_ligne->prix_unitaire }}" min="0"
                                                            step="0.01" oninput="saveChanges()" />
                                                    </div>
                                                </td>
                                                <td class="text-right py-2">
                                                    <button class="float-right"
                                                        data-matiere-id="{{ $cde_ligne->matiere_id }}"
                                                        onclick="removeMatiere(event)">
                                                        <x-icons.close size="2" class="icons" />
                                                    </button>
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
                        <div
                            class="bg-gradient-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800 w-full h-6 -mt-4 flex items-center justify-between px-6">
                            <h2 class="text-sm font-semibold">Total :</h2>
                            <h2 class="text-sm font-semibold" id="montant-total"></h2>
                        </div>
                    </form>
                    <div class="flex justify-between gap-4">
                        <div>
                            <button class="bg-red-500 hover:bg-red-600 btn"
                                onclick="event.preventDefault(); document.getElementById('confirm-delete-modal').classList.remove('hidden');">Supprimer</button>
                            <button class="btn" type="button" x-data
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
                            tr.setAttribute('data-matiere-ref-fournisseur', matiere.lastPriceRefFournisseur ||
                                '');
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
            var matiereRefFournisseur = event.currentTarget.getAttribute('data-matiere-ref-fournisseur');
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
                if (showRefFournisseur.checked == true) {
                    matiereRef = `
                    <div class="flex flex-col" id="refs-${matiereId}">

                        <div class="flex flex-col">
                            <span class="text-xs">Réf. Interne</span>
                            <span class="font-bold">${matiereRef || '-'}</span>
                            <input type="hidden" name="ref_interne[${matiereId}]" value="${matiereRef || ''}">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs">Réf. Fournisseur</span>
                            <x-text-input name="ref_fournisseur[${matiereId}]" value="${matiereRefFournisseur || ''}" class="font-bold p-0 border-0 bg-white dark:bg-gray-700 max-w-24" onblur="saveChanges()" />
                            </div>
                    </div>
                    <div class="flex flex-col hidden" id="ref-${matiereId}">

                        <div class="flex flex-col">
                            <span class="text-xs">Réf. Interne</span>
                            <span class="font-bold">${matiereRef || '-'}</span>
                        </div>
                    </div>
                    `;
                } else {
                    matiereRef = `
                    <div class="flex flex-col hidden" id="refs-${matiereId}">

                        <div class="flex flex-col">
                            <span class="text-xs">Réf. Interne</span>
                            <span class="font-bold">${matiereRef || '-'}</span>
                            <input type="hidden" name="ref_interne[${matiereId}]" value="${matiereRef || ''}">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs">Réf. Fournisseur</span>
                            <x-text-input name="ref_fournisseur[${matiereId}]" value="${matiereRefFournisseur || ''}" class="font-bold p-0 border-0 bg-white dark:bg-gray-700 w-auto" onblur="saveChanges()" />
                            </div>
                    </div>
                    <div class="flex flex-col " id="ref-${matiereId}">

                        <div class="flex flex-col">
                            <span class="text-xs">Réf. Interne</span>
                            <span class="font-bold">${matiereRef || '-'}</span>
                        </div>
                    </div>
                    `;
                }
                tr.innerHTML = `
    <td class="text-left ml-1">${matiereRef || '-'}</td>
    <td class="text-left py-2">${matiereDesignation || '-'}
        <input type="hidden" name="designation[${matiereId}]" value="${matiereDesignation || ''}">
        </td>
    <td class="text-right py-2">
        <div class="flex items-center justify-end">
            <!-- Boutons de quantité -->

            <x-text-input
                type="number"
                name="quantite[${matiereId}]"
                class="w-24"
                value="1"
                min="0"
                oninput="saveChanges()"
            />

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
                saveChanges();
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

        function saveChanges() {
            const cdeEntite = document.getElementById('cde-entite');
            const cdeNom = document.querySelector('input[name="cde-nom"]');
            const cdeId = document.getElementById('new-cde').textContent.trim();
            const saveStatus0 = document.getElementById('save-status-0');
            const saveStatus1 = document.getElementById('save-status-1');
            const saveStatus2 = document.getElementById('save-status-2');
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const searchbar = document.getElementById('searchbar');
            const montantTotal = document.getElementById('montant-total');
            const showRefFournisseurToggle = document.getElementById('show_ref_fournisseur');
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
            if (matiereChoisiTable.querySelector('tr[data-matiere-id]').value === '') {
                saveStatus0.classList.add('hidden');
                saveStatus2.classList.remove('hidden');
                return;
            }
            const matieres = [];
            var Total = 0;
            document.querySelectorAll('#matiere-choisi-table tr[data-matiere-id]').forEach(row => {
                const matiereId = row.getAttribute('data-matiere-id');
                const quantity = row.querySelector(`input[name="quantite[${matiereId}]"]`).value;
                const refInterne = row.querySelector(`input[name="ref_interne[${matiereId}]`).value;
                const refFournisseur = row.querySelector(`input[name="ref_fournisseur[${matiereId}]`).value;
                const designation = row.querySelector(`input[name="designation[${matiereId}]`).value;
                const prix = row.querySelector(`input[name="prix[${matiereId}]`).value;
                const unite = row.querySelector(`select[name="unite[${matiereId}]`).value;
                const date = row.querySelector(`input[name="date[${matiereId}]`).value;
                row.classList.remove('border-r-green-500', 'dark:border-r-green-600');
                if (quantity < 1) {
                    saveStatus0.classList.add('hidden');
                    saveStatus2.classList.remove('hidden');
                    return;
                }
                matieres.push({
                    id: matiereId,
                    quantite: quantity,
                    refInterne: refInterne,
                    refFournisseur: refFournisseur,
                    designation: designation,
                    prix: prix,
                    unite_id: unite,
                    date: date
                });
                row.classList.add('border-r-green-500', 'dark:border-r-green-600');
                cdeNom.classList.add('border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                cdeEntite.classList.add('border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                Total += parseFloat(row.querySelector(`input[name="prix[${matiereId}]`).value) * quantity;
            });
            montantTotal.textContent = Total.toFixed(3) + ' €';
            var showRefFournisseur = 0;
            if (showRefFournisseurToggle.checked) {
                var showRefFournisseur = 1;
            }
            console.log(showRefFournisseur);
            fetch('/cde/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cde_id: cdeId,
                        entite_id: cdeEntite.value,
                        show_ref_fournisseur: showRefFournisseur,
                        contact_id: document.getElementById('societe_contact_select').value,
                        total_ht: Total,
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
                    console.error('Erreur lors de la sauvegarde des données :', error);
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
            const matiereTable = document.getElementById('matiere-table');
            const cdeEntite = document.getElementById('cde-entite');
            const cdeNom = document.getElementById('cde-nom');
            const toggleShowRefFournisseur = document.getElementById('show_ref_fournisseur');

            // Event listener for search bar input

            searchbar.addEventListener('input', function() {
                liveSearch();
            });


            cdeNom.addEventListener('input', function() {
                if (cdeNom.value !== undefined && cdeNom.value.trim() !== '') {
                    saveChanges();
                }
            });
            cdeEntite.addEventListener('change', function() {
                saveChanges();
            });
            toggleShowRefFournisseur.addEventListener('change', function() {
                const refElements = document.querySelectorAll('[id^="refs-"]');
                refElements.forEach(element => {
                    if (toggleShowRefFournisseur.checked) {
                        element.classList.remove('hidden');
                    } else {
                        element.classList.add('hidden');
                    }
                });
                const refElements2 = document.querySelectorAll('[id^="ref-"]');
                refElements2.forEach(element => {
                    if (toggleShowRefFournisseur.checked) {
                        element.classList.add('hidden');
                    } else {
                        element.classList.remove('hidden');
                    }
                });
                saveChanges();
            });
        });
        // Add CSS for euro symbol
    </script>
</x-app-layout>
