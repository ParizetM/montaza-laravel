<x-app-layout>
    @section('title', 'Créer commande ' . $cde->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('cde.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Commandes</a>
                >>
                {!! __('Créer une commande') !!}
            </h2>
            <a href="{{ route('cde.annuler', $cde->id) }}" class="btn">Annuler la commande</a>

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
                class="shadow-xs sm:rounded-lg text-gray-900 dark:text-gray-100 px-2 grid grid-cols-1 sm:grid-cols-2  gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <h1 class="text-xl font-semibold mb-2">Sélection des matières</h1>
                    <div class="flex flex-wrap gap-2">
                        <!-- Famille selection dropdown -->
                        <div class="flex items-center gap-2 justify-between flex-wrap w-full">
                            <div class="flex items-center gap-2 flex-wrap">
                                <select name="famille" id="famille_id_search"
                                    class="px-4 py-2 border select mb-2 sm:mb-0 w-fit">
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
                                    class="px-4 py-2 border select mb-2 sm:mb-0 w-fit">
                                    <option value="" selected>{!! __('Toutes les sous-familles &nbsp;&nbsp;') !!}</option>
                                </select>
                            </div>
                            <x-quick-matiere class="justify-end" />
                        </div>
                        <!-- Search bar for materials -->
                        <div class="flex w-full">
                            <x-tooltip position="bottom" class="w-full">
                                <x-slot name="slot_item">
                                    <x-text-input placeholder="Recherchez une matière" id="searchbar" class="w-full" />
                                </x-slot>
                                <x-slot name="slot_tooltip">
                                    <ul class="whitespace-nowrap">
                                        <li>Recherchez par mots-clés</li>
                                        <li>Pour une <strong>référence fournisseur</strong>, remplacez les espaces par
                                            un <strong>"_"</strong></li>
                                        <li>Pour un <strong>DN</strong>, tapez "<strong>dn25</strong>"</li>
                                        <li>Pour une <strong>épaisseur</strong>, tapez "<strong>ep10</strong>"</li>
                                    </ul>
                                </x-slot>
                            </x-tooltip>
                            <button class="btn-select-right -ml-1 border-gray-300 dark:border-gray-700" type="button"
                                onclick="liveSearch()">Rechercher</button>
                        </div>
                    </div>
                    <div class="min-h-96 overflow-x-auto bg-gray-100 dark:bg-gray-900 rounded-sm">
                        <table>
                            <thead>
                                <tr>
                                    <th>Réference</th>
                                    <th>Sous-famille</th>
                                    <th>Matière</th>
                                    <th>Désignation</th>
                                    <th>DN</th>
                                    <th>EP</th>
                                    <th>Qté</th>
                                    <th>PU</th>
                                    <th>Date prix</th>
                                </tr>
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
                {{--
 ######   #######  ##              #####
##    ## ##     ## ##            ##     ##
##       ##     ## ##                   ##
##       ##     ## ##                 ##
##       ##     ## ##              ##
##    ## ##     ## ##            ##
 ######   #######  ########      #########  --}}

                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <form class="bg-white dark:bg-gray-800 flex flex-col gap-4 rounded-md">
                        @csrf
                        <input type="hidden" name="cde_id" value="{{ $cde->id ?? '' }}">
                        <div class="flex justify-between items-center">
                            <h1 class="text-xl font-semibold">Commande</h1>
                            <div class="flex items-center relative">
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 flex items-center hidden"
                                    title="Demande de prix en cours d'enregistrement" id="save-status-0">Enregistrement
                                    en
                                    cours...<x-icons.progress-activity size="2" /></h1>
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($cde) ? '' : 'hidden' }} absolute right-8 top-0 bg-white dark:bg-gray-800"
                                    title="Demande de prix enregistré avec succès" id="save-status-1">Enregistré</h1>
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($cde) ? 'hidden' : '' }} absolute right-8 top-0 bg-white dark:bg-gray-800 whitespace-nowrap"
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
                            <div class="w-full">
                                <x-input-label for="cde-nom" value="Nom" />
                                <x-text-input label="Nom" name="cde-nom" id="cde-nom"
                                    placeholder="Nom de la commande" autofocus
                                    value="{{ isset($cde) && $cde->nom != 'undefined' ? $cde->nom : '' }}"
                                    class="min-w-full {{ isset($cde) && $cde->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }}" />
                            </div>
                            <div class="w-fit">
                                <x-input-label for="cde-code" value="Code" />
                                <div
                                    class="flex items-center whitespace-nowrap bg-gray-100 dark:bg-gray-900 rounded-sm focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-600  {{ isset($cde) && $cde->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }}">
                                    <span class="ml-2"> CDE-{{ date('y') }}-</span>
                                    <x-text-input label="Code" name="cde-code" id="cde-code" placeholder="0000"
                                        autofocus maxlength="4"
                                        value="{{ isset($cde) && $cde->code != 'undefined' ? substr($cde->code, 7, 4) : '' }}"
                                        class="border-0 focus:border-0 dark:border-0 focus:ring-0 dark:focus:ring-0 w-14 px-0 mx-0" />
                                    <span class="-ml-2 mr-2"
                                        id="cde-code-entite">{{ isset($entite_code) ? $entite_code : '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <div>
                                <x-input-label for="societe_select" value="Fournisseur" />
                                <select name="societe_select" id="societe_select"
                                    class="select w-48 {{ $cde->hasSocieteContact() ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }}"
                                    onchange="selectSociete()">
                                    @if ($cde->societe_contact_id == null)
                                        <option value="" selected disabled>Choisir une société</option>
                                    @endif
                                    @foreach ($societes as $societe)
                                        <option value="{{ $societe->id }}"
                                            {{ $cde->hasSocieteContact() && $cde->societe->id == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->raison_sociale }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="">


                                <x-input-label for="etablissement_select" value="Établissement"
                                    id="etablissement_select_label"
                                    class="{{ $cde->hasSocieteContact() ? '' : 'hidden' }} " />
                                <select name="etablissement_select" id="etablissement_select"
                                    class="select w-auto {{ $cde->hasSocieteContact() ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : 'hidden' }}"
                                    onchange="etablissementSelect()">
                                    @if ($cde->hasSocieteContact())
                                        @foreach ($cde->societe->etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}"
                                                {{ $cde->hasSocieteContact() && $cde->etablissement->id == $etablissement->id ? 'selected' : '' }}>
                                                {{ $etablissement->nom }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="{{ $cde->hasSocieteContact() ? '' : 'hidden' }}"
                                id="societe_contact_select_div">


                                @php
                                    $options = [];
                                    if (!($cde->societeContacts->count() == 0)) {
                                        foreach ($cde->societe->societeContacts as $contact) {
                                            $options[$contact->id] =
                                                $contact->nom .
                                                ' <small class="text-gray-500 whitespace-nowrap">' .
                                                $contact->email .
                                                '</small>';
                                        }
                                    }
                                    if ($cde->hasSocieteContact()) {
                                        $selected_options = [];
                                        foreach ($cde->societeContacts as $contact) {
                                            $selected_options[] = $contact->id;
                                        }
                                    }

                                @endphp
                                <x-input-label for="societe_contact_select" value="Destinataire(s)"
                                    id="societe_contact_select_label" />

                                <x-select-multiple :options="$options" name="societe_contact_select" :selected="$selected_options ?? []"
                                    id="societe_contact_select" placeholder="Sélectionner un contact"
                                    emptyMessage="Aucun contact trouvé" class="w-full" />
                            </div>

                        </div>
                        <div class="mt-auto">
                            <x-toggle name="show_ref_fournisseur" id="show_ref_fournisseur" :checked="$showRefFournisseur"
                                label="Afficher les références fournisseur" />
                        </div>
                        <div class="min-h-96 overflow-x-auto bg-gray-100 dark:bg-gray-900 rounded-sm">
                            <table>
                                <thead>
                                    <tr>
                                        <th colspan="100" class="border-r-4 border-gray-50 dark:border-gray-800">
                                            Matières
                                            sélectionnées</th>
                                    </tr>
                                    <tr>
                                        <th>Réference</th>
                                        <th>Désignation</th>
                                        <th>
                                            <div class="float-left">Quantité</div>
                                            <div class="float-right">Date de livraison</div>
                                        </th>
                                        <th>Prix unitaire</th>
                                        <th class="border-r-4 border-gray-50 dark:border-gray-800"></th>
                                    </tr>
                                </thead>
                                <tbody id="matiere-choisi-table">
                                    @foreach ($cde->cdeLignes as $cde_ligne)
                                        @if ($cde_ligne->ligne_autre_id == null)
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
                                                                class="font-bold p-0 border-0 bg-gray-200 dark:bg-gray-700 max-w-24 mb-1"
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
                                                        <div
                                                            class="flex items-center focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-600 focus-within:focus:border-indigo-600 rounded-sm m-1">

                                                            <x-text-input type="number"
                                                                name="quantite[{{ $cde_ligne->matiere->id }}]"
                                                                oninput="saveChanges()"
                                                                class="w-20 border-r-0 rounded-r-none  dark:border-r-0 focus:ring-0 focus:border-0 dark:focus:ring-0"
                                                                value="{{ formatNumber($cde_ligne->quantite) }}"
                                                                min="0" />
                                                            <div
                                                                class="text-right bg-gray-100 dark:bg-gray-900 w-fit p-2.5 pl-0 border-1 border-l-0 rounded-r-sm border-gray-300 dark:border-gray-700 ">
                                                                {{ $cde_ligne->matiere->unite->short }}</div>
                                                        </div>
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
                                                    <button class="float-right" type="button"
                                                        data-matiere-id="{{ $cde_ligne->matiere_id }}"
                                                        onclick="removeMatiere(event)">
                                                        <x-icons.close size="2" class="icons"
                                                            tabindex="-1" />
                                                    </button>
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="border-b border-gray-200 dark:border-gray-700 rounded-r-md overflow-hidden bg-white dark:bg-gray-800 border-r-green-500 dark:border-r-green-600 border-r-4 text-sm"
                                                data-matiere-id="{{ $cde_ligne->ligne_autre_id }}">
                                                <td class="text-left ml-1">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs">Réf. Interne</span>
                                                        <x-text-input
                                                            name="ref_interne[{{ $cde_ligne->ligne_autre_id }}]"
                                                            value="{{ $cde_ligne->ref_interne ?? '' }}"
                                                            class="font-bold p-0 border-0 bg-gray-200 dark:bg-gray-700 max-w-24 mb-1"
                                                            onblur="saveChanges()" />
                                                    </div>
                                                    <div class="flex flex-col {{ $showRefFournisseur ? '' : 'hidden' }}"
                                                        id="refs-{{ $cde_ligne->ligne_autre_id }}">
                                                        <div class="flex flex-col">
                                                            <span class="text-xs">Réf. Fournisseur</span>
                                                            <x-text-input
                                                                name="ref_fournisseur[{{ $cde_ligne->ligne_autre_id }}]"
                                                                value="{{ $cde_ligne->ref_fournisseur ?? '' }}"
                                                                class="font-bold p-0 border-0 bg-gray-200 dark:bg-gray-700 max-w-24 mb-1"
                                                                onblur="saveChanges()" />
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-left py-2">
                                                    <textarea name="designation[{{ $cde_ligne->ligne_autre_id }}]"
                                                        class="w-full m-0 dark:bg-gray-900 rounded dark:border-gray-700 resize-none" oninput="saveChanges()">{{ $cde_ligne->designation ?? '' }}</textarea>
                                                </td>
                                                <td class="text-right py-2">
                                                    <div class="flex items-center justify-end">
                                                        <div
                                                            class="flex items-center focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-600 focus-within:focus:border-indigo-600 rounded-sm m-1">

                                                            <x-text-input type="number"
                                                                name="quantite[{{ $cde_ligne->ligne_autre_id }}]"
                                                                oninput="saveChanges()" class="w-20 "
                                                                value="{{ formatNumber($cde_ligne->quantite) }}"
                                                                min="0" />

                                                        </div>
                                                        <x-date-input name="date[{{ $cde_ligne->ligne_autre_id }}]"
                                                            class="w-fit" value="{{ $cde_ligne->date_livraison }}"
                                                            oninput="saveChanges()" />
                                                    </div>
                                                </td>
                                                <td class="text-left py-2">
                                                    <div class="price-input-container flex items-center">
                                                        <x-text-input type="number"
                                                            name="prix[{{ $cde_ligne->ligne_autre_id }}]"
                                                            class="price-input"
                                                            value="{{ $cde_ligne->prix_unitaire }}" min="0"
                                                            step="0.01" oninput="saveChanges()" />
                                                    </div>
                                                </td>
                                                <td class="text-right py-2">
                                                    <button class="float-right" type="button"
                                                        data-matiere-id="{{ $cde_ligne->ligne_autre_id }}"
                                                        onclick="removeMatiere(event)">
                                                        <x-icons.close size="2" class="icons"
                                                            tabindex="-1" />
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="w-full flex justify-end gap-2 text-center">
                                <button type="button"
                                    class="btn w-fit rounded-none rounded-bl-xl bg-white dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 hover:shadow-lg transition-all duration-300 py-0 px-4 mt-0"
                                    onclick="addLigneVide()" title="Ajouter une ligne vide">
                                    <span class="text-center w-full text-4xl">
                                        +
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div
                            class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800 w-full h-6 -mt-4 flex items-center justify-between px-6">
                            <h2 class="text-sm font-semibold">Total :</h2>
                            <h2 class="text-sm font-semibold" id="montant-total"></h2>
                        </div>
                    </form>
                    <div class="flex justify-between gap-4">
                        <div>
                            <button
                                class="bg-red-500 hover:bg-red-600 btn hover:cursor-pointer text-white dark:text-gray-100"
                                x-data x-on:click="$dispatch('open-modal', 'delete-commande-modal')">Supprimer</button>
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
                                    <button class=" text-white px-4 py-2 rounded-sm btn"
                                        x-on:click="$dispatch('close')">Annuler</button>
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-sm"
                                        onclick="window.location.href = '{{ route('cde.create') }}'">Réinitialiser</button>
                                </div>
                            </div>
                        </x-modal>
                        <x-modal name="delete-commande-modal" title="Supprimer" max-width="5xl">
                            <div class="flex flex-col gap-4 p-4 text-gray-900 dark:text-gray-100">
                                <div class="flex justify-between items-center">
                                    <h1 class="text-xl font-semibold">Supprimer la commande</h1>
                                    <a x-on:click="$dispatch('close')">
                                        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                                    </a>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">Voulez-vous vraiment supprimer cette
                                    commande ? Cette action est irréversible.</p>
                                <div class="flex justify-end gap-4">
                                    <button class=" text-white px-4 py-2 rounded-sm btn"
                                        x-on:click="$dispatch('close')">Annuler</button>
                                    <form action="{{ route('cde.destroy', ['cde' => $cdeid]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-sm hover:cursor-pointer">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </x-modal>
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
                            option.textContent = sousFamille.nom + ' ';
                            option.style.display = 'flex';
                            option.style.justifyContent = 'space-between';
                            option.textContent = `${sousFamille.nom} (${sousFamille.matiere_count})`;
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
            var etablissementSelectLabel = document.getElementById('etablissement_select_label');
            const societeContactSelect = document.getElementById('societe_contact_select');
            var societeContactSelectDiv = document.getElementById('societe_contact_select_div');

            etablissementSelectDOM.innerHTML = '';
            etablissementSelectLabel.classList.add('hidden');
            etablissementSelectDOM.classList.add('hidden');
            societeContactSelectDiv.classList.add('hidden');
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
                        etablissementSelectLabel.classList.remove('hidden');
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
                        etablissementSelectLabel.classList.remove('hidden');
                    });

                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des établissements :', error);
                });
        }

        function etablissementSelect() {
            var etablissementId = document.getElementById('etablissement_select').value;
            var societeContactSelectDiv = document.getElementById('societe_contact_select_div');
            var societeContactContainer = document.getElementById('societe_contact_select-container');
            var societeId = document.getElementById('societe_select').value;
            var societeContactSelect = document.getElementById('societe_contact_select');
            societeContactSelectDiv.classList.add('hidden');
            if (societeContactContainer && Alpine) {
                Alpine.evaluate(societeContactContainer, '$data.clearSelected()');
            }
            fetch(`/societes/${societeId}/etablissements/${etablissementId}/contacts/json`)
                .then(response => response.json())
                .then(data => {
                    // Afficher le conteneur si masqué
                    societeContactSelectDiv.classList.remove('hidden');
                    if (data.length == 0) {
                        return;
                    }
                    // Formatter les données pour le select multiple
                    let formattedOptions = {};
                    data.forEach(contact => {
                        formattedOptions[contact.id] = contact.nom +
                            ' <small class="text-gray-500 whitespace-nowrap">' + contact.email + '</small>';
                    });

                    // Version alternative utilisant la méthode updateOptions
                    if (societeContactContainer && Alpine) {
                        Alpine.evaluate(societeContactContainer, '$data.updateOptions($el.dataset.newOptions)');
                        societeContactContainer.dataset.newOptions = JSON.stringify(formattedOptions);
                    }

                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des contacts :', error);
                });
        }

        let debounceSearchPrixTimeout = null;
        let currentPrixSearchController = null;

        function liveSearch() {
            clearTimeout(debounceSearchPrixTimeout);

            debounceSearchPrixTimeout = setTimeout(() => {
                const searchbar = document.getElementById('searchbar');
                const search = searchbar.value;
                const familleId = document.getElementById('famille_id_search').value;
                const sousFamilleId = document.getElementById('sous_famille_id_search').value;
                const matiereTable = document.getElementById('matiere-table');
                const societeContactSelect = document.getElementById('societe_contact_select');
                const societe_select = document.getElementById('societe_select');

                // Vérifie que le destinataire est bien sélectionné
                if (societeContactSelect.value === "[]") {
                    searchbar.classList.add('border-red-500', 'dark:border-red-600', 'border-2',
                        'focus:border-red-500', 'dark:focus:border-red-600');
                    showFlashMessageFromJs('Veuillez d\'abord sélectionner un destinataire', 2000, 'error');
                    searchbar.blur();
                    societe_select.focus();
                    matiereTable.innerHTML = '';
                    return;
                }

                // Annule la requête précédente si elle existe
                if (currentPrixSearchController) {
                    currentPrixSearchController.abort();
                }

                // Spinner de chargement
                matiereTable.innerHTML = `
            <tr>
                <td colspan="100">
                    <div id="loading-spinner" class="mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full">
                        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
                    </div>
                    <style>
                        .loader {
                            border-top-color: #3498db;
                            animation: spinner 1.5s linear infinite;
                        }
                        @keyframes spinner {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                    </style>
                </td>
            </tr>
                `;
                currentPrixSearchController = new AbortController();
                const {
                    signal
                } = currentPrixSearchController;

                const url =
                    `/matieres/quickSearch?search=${encodeURIComponent(search)}&famille=${familleId}&sous_famille=${sousFamilleId}&with_last_price=1&societe=${societe_select.value}`;
                // console.log(url);

                fetch(url, {
                        signal
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur lors de la récupération des données');
                        return response.json();
                    })
                    .then(data => {
                        matiereTable.innerHTML = '';

                        if (data.matieres && data.matieres.length > 0) {
                            data.matieres.forEach(matiere => {
                                const tr = document.createElement('tr');
                                tr.classList.add(
                                    'border-b', 'border-gray-200', 'dark:border-gray-700',
                                    'rounded-r-md', 'overflow-hidden', 'bg-white',
                                    'dark:bg-gray-800',
                                    'cursor-pointer', 'hover:bg-gray-200', 'dark:hover:bg-gray-700'
                                );

                                tr.setAttribute('data-matiere-id', matiere.id || '');
                                tr.setAttribute('data-matiere-ref', matiere.refInterne || '');
                                tr.setAttribute('data-matiere-ref-fournisseur', matiere.refexterne ||
                                    '');
                                tr.setAttribute('data-matiere-designation', matiere.designation || '');
                                tr.setAttribute('data-prix', matiere.lastPrice || '');
                                tr.setAttribute('data-matiere-unite', matiere.lastPriceUnite || matiere
                                    .Unite || '');
                                tr.addEventListener('click', addMatiere);

                                if (matiere.lastPrice) {
                                    tr.innerHTML = `
                                <td class="text-left px-2">${matiere.refInterne || '-'}</td>
                                <td class="text-right px-2">${matiere.sousFamille || '-'}</td>
                                <td class="text-left px-2">${matiere.material || '-'}</td>
                                <td class="text-left px-2">${matiere.designation || '-'}</td>
                                <td class="text-left px-2">${matiere.dn || '-'}</td>
                                <td class="text-left px-2">${matiere.epaisseur || '-'}</td>
                                <td class="text-left px-2">${matiere.quantite + ' ' + matiere.Unite || '-'}</td>
                                <td class="text-right px-2 font-bold whitespace-nowrap">${matiere.lastPrice_formated + '/' + matiere.Unite}</td>
                                <td class="text-left px-2">${matiere.lastPriceDate || '-'}</td>
                            `;
                                } else {
                                    tr.innerHTML = `
                                <td class="text-left px-2">${matiere.refInterne || '-'}</td>
                                <td class="text-right px-2">${matiere.sousFamille || '-'}</td>
                                <td class="text-left px-2">${matiere.material || '-'}</td>
                                <td class="text-left px-2">${matiere.designation || '-'}</td>
                                <td class="text-left px-2">${matiere.dn || '-'}</td>
                                <td class="text-left px-2">${matiere.epaisseur || '-'}</td>
                                <td class="text-left px-2">${matiere.tooltip || '-'}</td>
                                <td class="text-center pr-6" colspan="2">Aucun prix</td>
                            `;
                                }

                                matiereTable.appendChild(tr);
                            });
                        } else {
                            matiereTable.innerHTML = `
                        <tr><td colspan="100" class="text-gray-500 dark:text-gray-400 text-center">Aucune matière trouvée</td></tr>
                    `;
                        }
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            console.error('Erreur lors de la recherche :', error);
                        }
                    });
            }, 300); // délai de debounce
        }

        // Function to add selected material to the chosen list
        function addMatiere(event) {
            const matiereId = event.currentTarget.getAttribute('data-matiere-id');
            var matiereRef = event.currentTarget.getAttribute('data-matiere-ref');
            var matiereRefFournisseur = event.currentTarget.getAttribute('data-matiere-ref-fournisseur');
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
                            <x-text-input name="ref_fournisseur[${matiereId}]" value="${matiereRefFournisseur || ''}" class="font-bold p-0 border-0 bg-gray-200 dark:bg-gray-700 max-w-24" onblur="saveChanges()" />
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
                            <x-text-input name="ref_fournisseur[${matiereId}]" value="${matiereRefFournisseur || ''}" class="font-bold p-0 border-0 bg-gray-200 dark:bg-gray-700 w-auto" onblur="saveChanges()" />
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

            <div
                class="flex items-center focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-600 focus-within:focus:border-indigo-600 rounded-sm m-1">
                <x-text-input
                    type="number"
                    name="quantite[${matiereId}]"
                    class="w-20 border-r-0 rounded-r-none dark:border-r-0 focus:ring-0 focus:border-0 dark:focus:ring-0"
                    value="1"
                    min="0"
                    oninput="saveChanges()"
                />
                <div
                    class="text-right bg-gray-100 dark:bg-gray-900 w-fit p-2.5 pl-0 border-1 border-l-0 rounded-r-sm border-gray-300 dark:border-gray-700">
                    ${unites.find(unite => unite.short === matiereUnite)?.short || ''}
                </div>
            </div>
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
            tabindex="-1"
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

        function addLigneVide() {
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const showRefFournisseur = document.getElementById('show_ref_fournisseur');
            const tr = document.createElement('tr');
            id = "ligne_autre_id-" + Date.now();
            if (document.querySelector(`tr[data-matiere-id="${id}"]`)) {
                id = id + Math.floor(Math.random() * 1000) + 1;
            }
            tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                'rounded-r-md', 'overflow-hidden', 'bg-white', 'dark:bg-gray-800', 'border-r-4');
            tr.setAttribute('data-matiere-id', id);

            tr.innerHTML = `
                    <tr class="border-b border-gray-200 dark:border-gray-700 rounded-r-md overflow-hidden bg-white dark:bg-gray-800 border-r-green-500 dark:border-r-green-600 border-r-4 text-sm"
                        data-matiere-id="${id}">
                        <td class="text-left ml-1">
                            <div class="flex flex-col ${showRefFournisseur.checked ? '' : 'hidden'}"
                                id="refs-${id}">
                                <div class="flex flex-col">
                                    <span class="text-xs">Réf. Interne</span>
                                    <x-text-input
                                        name="ref_interne[${id}]"
                                        value=""
                                        class="font-bold p-0 border-0 bg-gray-200 dark:bg-gray-700 max-w-24 mb-1"
                                        onblur="saveChanges()" />
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs">Réf. Fournisseur</span>
                                    <x-text-input
                                        name="ref_fournisseur[${id}]"
                                        value=""
                                        class="font-bold p-0 border-0 bg-gray-200 dark:bg-gray-700 max-w-24 mb-1"
                                        onblur="saveChanges()" />
                                </div>
                            </div>
                            <div class="flex flex-col ${showRefFournisseur.checked ? 'hidden' : ''}"
                                id="ref-${id}">
                                <div class="flex flex-col">
                                    <span class="text-xs">Réf. Interne</span>
                                    <x-text-input
                                        name="ref_interne[${id}]"
                                        value=""
                                        class="font-bold p-0 border-0 bg-gray-200 dark:bg-gray-700 max-w-24 mb-1"
                                        onblur="saveChanges()" />
                                </div>
                            </div>
                        </td>
                        <td class="text-left py-2">
                            <textarea name="designation[${id}]"
                                class="w-full m-0 dark:bg-gray-900 rounded dark:border-gray-700 resize-none" oninput="saveChanges()"></textarea>
                        </td>
                        <td class="text-right py-2">
                            <div class="flex items-center justify-end">
                                <div
                                    class="flex items-center focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-600 focus-within:focus:border-indigo-600 rounded-sm m-1">
                                    <x-text-input type="number"
                                        name="quantite[${id}]"
                                        oninput="saveChanges()"
                                        class="w-20 "
                                        value="0"
                                        min="0" />
                                </div>
                                <x-date-input name="date[${id}]"
                                    class="w-fit" value=""
                                    oninput="saveChanges()" />
                            </div>
                        </td>
                        <td class="text-left py-2">
                            <div class="price-input-container flex items-center">
                                <x-text-input type="number"
                                    name="prix[${id}]"
                                    class="price-input"
                                    value="0"
                                    min="0"
                                    step="0.01" oninput="saveChanges()" />
                            </div>
                        </td>
                        <td class="text-right py-2">
                            <button class="float-right"
                                data-matiere-id="${id}"
                                onclick="removeMatiere(event)">
                                <x-icons.close size="2" class="icons"
                                    tabindex="-1" />
                            </button>
                        </td>
                    </tr>
            `;
            matiereChoisiTable.appendChild(tr);
            document.getElementById('societe_select').disabled = false;
            saveChanges();
        }

        // Function to increment the quantity of selected material

        function saveChanges() {
            const cdeEntite = document.getElementById('cde-entite');
            const cdeNom = document.querySelector('input[name="cde-nom"]');
            const cdeCode = document.getElementById('cde-code');
            const cdeCodeEntite = document.getElementById('cde-code-entite');
            const cdeId = document.getElementById('new-cde').textContent.trim();
            const saveStatus0 = document.getElementById('save-status-0');
            const saveStatus1 = document.getElementById('save-status-1');
            const saveStatus2 = document.getElementById('save-status-2');
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const searchbar = document.getElementById('searchbar');
            const montantTotal = document.getElementById('montant-total');
            const showRefFournisseurToggle = document.getElementById('show_ref_fournisseur');
            const societeContactContainer = document.getElementById('societe_contact_select-container');
            searchbar.classList.remove('border-red-500', 'dark:border-red-600', 'border-2', 'focus:border-red-500',
                'dark:focus:border-red-600');
            saveStatus0.classList.remove('hidden');
            saveStatus1.classList.add('hidden');
            saveStatus2.classList.add('hidden');
            checkContact();
            if ('' === cdeCode.value.trim()) {
                saveStatus0.classList.add('hidden');
                saveStatus2.classList.remove('hidden');
                return;
            }
            if ('' === cdeNom.value.trim()) {
                cdeNom.value = 'CDE-' + new Date().getFullYear().toString().slice(-2) + '-' + cdeCode.value + cdeCodeEntite
                    .textContent;
            }
            if (cdeId === '') {
                saveStatus0.classList.add('hidden');
                saveStatus2.classList.remove('hidden');
                return;
            }
            // if (!matiereChoisiTable.querySelector('tr[data-matiere-id]')) {

            //     saveStatus0.classList.add('hidden');
            //     saveStatus2.classList.remove('hidden');
            //     return;
            // }
            // if (matiereChoisiTable.querySelector('tr[data-matiere-id]').value === '') {
            //     saveStatus0.classList.add('hidden');
            //     saveStatus2.classList.remove('hidden');
            //     return;
            // }
            const matieres = [];
            var Total = 0;
            document.querySelectorAll('#matiere-choisi-table tr[data-matiere-id]').forEach(row => {
                const matiereId = row.getAttribute('data-matiere-id');
                const quantity = row.querySelector(`input[name = "quantite[${matiereId}]"] `).value;
                const refInterne = row.querySelector(`input[name = "ref_interne[${matiereId}]`).value;
                const refFournisseur = row.querySelector(`input[name="ref_fournisseur[${matiereId}]`).value;
                const designation = row.querySelector(`input[name="designation[${matiereId}]`) ?
                    row.querySelector(`input[name="designation[${matiereId}]`).value :
                    row.querySelector(`textarea[name="designation[${matiereId}]`).value;
                const prix = row.querySelector(`input[name="prix[${matiereId}]`).value;
                // const unite = row.querySelector(`select[name="unite[${matiereId}]`).value;
                const date = row.querySelector(`input[name="date[${matiereId}]`).value;
                row.classList.remove(
                    'border-r-green-500', 'dark:border-r-green-600');
                if (quantity < 1 || isNaN(parseFloat(quantity)) || isNaN(parseFloat(prix)) || quantity.endsWith(
                    '.') || prix.endsWith('.')) {
                    saveStatus0.classList.add('hidden');
                    saveStatus2.classList.remove('hidden');
                    return;
                }
                if (matiereId.startsWith('ligne_autre_id')) {
                    matieres.push({
                        ligne_autre_id: matiereId,
                        quantite: quantity,
                        refInterne: refInterne,
                        refFournisseur: refFournisseur,
                        designation: designation,
                        prix: prix,
                        // unite_id: unite,
                        date: date
                    });
                } else {
                    matieres.push({
                        id: matiereId,
                        quantite: quantity,
                        refInterne: refInterne,
                        refFournisseur: refFournisseur,
                        designation: designation,
                        prix: prix,
                        // unite_id: unite,
                        date: date
                    });
                }

                row.classList.add('border-r-green-500', 'dark:border-r-green-600');
                Total +=
                    parseFloat(row.querySelector(`input[name="prix[${matiereId}]`).value) * quantity;
            });
            cdeNom.classList.add(
                'border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
            cdeCode.classList.add(
                'border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
            cdeEntite.classList.add(
                'border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
            societeContactContainer.classList.add(
                'border-r-green-500', 'dark:border-r-green-600', 'border-r-4');

            if (cdeEntite.value == 1) {
                cdeCodeEntite.textContent = '';
            } else if (cdeEntite.value == 2) {
                cdeCodeEntite.textContent = 'AV';
            } else if (cdeEntite.value == 3) {
                cdeCodeEntite.textContent = 'AMB';
            } else {
                cdeCodeEntite.textContent = '';
            }
            document.title =
                `Créer - CDE-${new Date().getFullYear().toString().slice(-2)}-${cdeCode.value}${cdeCodeEntite.textContent}`;
            montantTotal.textContent = Total.toFixed(3) + ' €';
            var showRefFournisseur = 0;
            if (showRefFournisseurToggle.checked) {
                var showRefFournisseur = 1;
            }
            fetch('/cde/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cde_id: cdeId,
                        entite_id: cdeEntite.value,
                        code: cdeCode.value,
                        show_ref_fournisseur: showRefFournisseur,
                        contact_id: document.getElementById('societe_contact_select').value,
                        // total_ht: Total,
                        nom: cdeNom.value,
                        matieres: matieres
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        // Parse the JSON error response to get the error message
                        return response.json().then(errData => {
                            throw new Error(errData.error || 'Une erreur est survenue');
                        });
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
                    showFlashMessageFromJs(
                        error.message || 'Erreur lors de la sauvegarde des données', 2000, 'error');
                    cdeNom.classList.remove(
                        'border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                    cdeCode.classList.remove(
                        'border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                    cdeEntite.classList.remove(
                        'border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                    societeContactContainer.classList.remove(
                        'border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                });
        }

        function removeMatiere(event) {
            const matiereId = event.target.getAttribute('data-matiere-id');
            const row = event.target.closest('tr');
            row.remove();
            saveChanges();
        }

        function checkContact() {
            const cdeContacts = document.getElementById('societe_contact_select');

            if (cdeContacts.value.length > 2 || cdeContacts.value != '[]') {
                // If contacts are selected, disable the societe and etablissement selects
                document.getElementById('societe_select').disabled = true;
                document.getElementById('etablissement_select').disabled = true;
            } else {
                // If no contact is selected, re-enable the selects
                document.getElementById('societe_select').disabled = false;
                document.getElementById('etablissement_select').disabled = false;

            }
        }

        // DOM CONTENT LOADED ///////////////////////////////////////////////////////////////////
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
            const cdeContacts = document.getElementById('societe_contact_select');
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');

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

                fetch('/cde/get-last-code/' + cdeEntite.value, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.title =
                            `Créer CDE-${new Date().getFullYear().toString().slice(-2)}-${data.code}${data.entite_code}`;
                        document.getElementById('cde-code').value = data.code;
                        document.getElementById('cde-code-entite').textContent = data.entite_code;
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération du code :', error);
                    });
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
            cdeContacts.addEventListener('change', function() {
                // Disable fournisseur and etablissement selects if they have values
                console.log(cdeContacts.value);
                checkContact();
                if (cdeContacts.value.length > 2 || cdeContacts.value != '[]') {
                    saveChanges();
                }
            });
            if (matiereChoisiTable.querySelector('tr[data-matiere-id]')) {
                setTimeout(() => {
                    saveChanges();
                }, 100);
            }
        });
    </script>
</x-app-layout>
