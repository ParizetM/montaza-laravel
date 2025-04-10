@php
    use Carbon\Carbon;
@endphp

<x-app-layout>
    @section('title', 'Validation - ' . $cde->code)
    <x-slot name="header">

        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix et commandes</a>
                >>
                <a href="{{ route('cde.show', $cde->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une commande') !!}</a>
                >> Validation
            </h2>
            @if ($listeChangement != false)
                <div class="flex items-center">
                    <button x-data x-on:click="$dispatch('open-modal', 'listeChangement-modal')" type="button"
                        id="open-modal-listeChangement" class="btn btn-primary">
                        <span class="ml-2">Liste des changements</span>
                    </button>
                </div>
            @endif
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
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <form action="{{ route('cde.validate', $cde->id) }}" method="POST">
            @csrf
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
                <div class="flex items-center mb-6">
                    <h1 class="text-3xl font-bold  text-left mr-2">{{ $cde->code }} - {{ $cde->nom }}</h1>
                    <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                        style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                        {{ $cde->statut->nom }}</div>
                </div>


                {{--
##     ##    ###    ##     ## ########      ########  ########      ########     ###     ######   ########
##     ##   ## ##   ##     ##    ##         ##     ## ##            ##     ##   ## ##   ##    ##  ##
##     ##  ##   ##  ##     ##    ##         ##     ## ##            ##     ##  ##   ##  ##        ##
######### ##     ## ##     ##    ##         ##     ## ######        ########  ##     ## ##   #### ######
##     ## ######### ##     ##    ##         ##     ## ##            ##        ######### ##    ##  ##
##     ## ##     ## ##     ##    ##         ##     ## ##            ##        ##     ## ##    ##  ##
##     ## ##     ##  #######     ##         ########  ########      ##        ##     ##  ######   ########
 --}}
                <h2 class="text-xl font-bold mb-6 text-left border-b-2 border-gray-200 dark:border-gray-700 p-2">Haut
                    de page</h2>
                <div class="flex justify-between">
                    <div class="flex flex-col gap-4 m-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-4">
                                    <x-input-label value="Numéro d'affaire" />
                                    <small>(Optionnel)</small>
                                </div>
                                <x-text-input name="numero_affaire" :value="old('numero_affaire', $cde->affaire_numero)" />
                                @error('numero_affaire')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-4">
                                    <x-input-label value="Nom d'affaire" />
                                    <small>(Optionnel)</small>
                                </div>
                                <x-text-input name="nom_affaire" :value="old('nom_affaire', $cde->affaire_nom)" />
                                @error('nom_affaire')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-4">
                                    <x-input-label value="Numéro de devis" />
                                    <small>(Optionnel)</small>
                                </div>
                                <x-text-input name="numero_devis" :value="old('numero_devis', $cde->devis_numero)" />
                                @error('numero_devis')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-4">
                                <x-input-label value="Affaire suivi par " />
                                <small>(Optionnel)</small>
                            </div>
                            <select name="affaire_suivi_par" class="select w-fit min-w-96">
                                <option value=""
                                    {{ old('affaire_suivi_par', $cde->affaire_suivi_par_id) == 0 ? 'selected' : '' }}>
                                    Non
                                    suivi</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('affaire_suivi_par', $cde->affaire_suivi_par_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('affaire_suivi_par')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-4">
                                <x-input-label value="Acheteur " />
                                <small>(Optionnel)</small>
                            </div>
                            <select name="acheteur_id" class="select w-fit min-w-96">
                                <option value=""
                                    {{ old('acheteur_id', $cde->acheteur_id) == 0 ? 'selected' : '' }}>
                                    Sans Acheteur
                                </option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('acheteur_id', $cde->acheteur_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('acheteur_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex gap-4">
                            <x-toggle :checked="old('afficher_destinataire', true)" :label="'Afficher le destinataire dans le PDF ?'" id="afficher_destinataire"
                                name="afficher_destinataire" class="toggle-class" />
                            @error('afficher_destinataire')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <img src="{{ asset($entite->logo) }}" alt="Logo"
                        class="w-1/4 h-1/4 mb-4 object-contain float-right">
                </div>
                {{--
 ######   #######  ########  ########   ######
##    ## ##     ## ##     ## ##     ## ##    ##
##       ##     ## ##     ## ##     ## ##
##       ##     ## ########  ########   ######
##       ##     ## ##   ##   ##              ##
##    ## ##     ## ##    ##  ##        ##    ##
 ######   #######  ##     ## ##         ######
--}}
                <h2 class="text-xl font-bold mb-6 text-left border-b-2 border-gray-200 dark:border-gray-700 p-2">Corps
                    de la commande</h2>
                <div class="flex">
                    <div class="m-4">
                        <x-input-label value="TVA (%)" />
                        <x-text-input name="tva" type="number" :value="old('tva', $cde->tva)" onblur="recalculateTotal()" />
                        @error('tva')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col m-4">
                        <div class="flex gap-4">
                            <x-input-label value="Frais de port" />
                            <small>(Optionnel)</small>
                        </div>
                        <div class="price-input-container">
                            <x-text-input name="frais_de_port" type="number" step="0.01" :value="old('frais_de_port', $cde->frais_de_port)"
                                onblur="recalculateTotal()" class=" price-input" />
                        </div>
                        @error('frais_de_port')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <div class="flex flex-col m-4">
                            <div class="flex gap-4">
                                <x-input-label value="Frais divers" />
                                <small>(Optionnel)</small>
                            </div>
                            <div class="price-input-container">
                                <x-text-input name="frais_divers" type="number" step="0.01" :value="old('frais_divers', $cde->frais_divers)"
                                    onblur="fraisDiversChange();recalculateTotal()" class="price-input" />
                            </div>
                            @error('frais_divers')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col m-4 {{ $cde->frais_divers == null ? 'hidden' : '' }}">
                            <div class="flex gap-4">
                                <x-input-label value="Description des frais divers" />
                                <small>(Optionnel)</small>
                            </div>
                            <x-text-input name="frais_divers_texte" :value="old('frais_divers_texte', $cde->frais_divers_texte)" />
                            @error('frais_divers_texte')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <script>
                            function fraisDiversChange() {
                                const fraisDivers = document.querySelector('input[name="frais_divers"]');
                                const fraisDiversTexte = document.querySelector('input[name="frais_divers_texte"]');
                                if (fraisDivers.value != '') {
                                    fraisDiversTexte.parentElement.classList.remove('hidden');
                                } else {
                                    fraisDiversTexte.parentElement.classList.add('hidden');
                                }
                            }
                        </script>
                    </div>
                </div>
                <table class="min-w-0 bg-gray-100 dark:bg-gray-900 ">
                    <thead>
                        <tr>
                            <th class="text-left">Référence</th>
                            <th class="text-left">Désignation</th>
                            <th class="text-left px-1">Quantité</th>
                            <th class="text-left">Prix unitaire</th>
                            <th class="text-left">Total HT</th>
                            <th class="text-left">date de livraison</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cde->cdeLignes as $ligne)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="text-left ml-1 p-2">
                                    <div class="flex flex-col {{ $showRefFournisseur ? '' : 'hidden' }}"
                                        id="refs-{{ $ligne->matiere_id }}">
                                        <div class="flex flex-col">
                                            <span class="text-xs">Réf. Interne</span>
                                            <span class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs">Réf. Fournisseur</span>
                                            <span class="font-bold">{{ $ligne->ref_fournisseur ?? '-' }}</span>

                                        </div>
                                    </div>
                                    <div class="flex flex-col {{ $showRefFournisseur ? 'hidden' : '' }}"
                                        id="ref-{{ $ligne->matiere_id }}">
                                        <div class="flex flex-col">
                                            <span class="text-xs">Réf. Interne</span>
                                            <span class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-2 text-left border border-gray-200 dark:border-gray-700">
                                    {{ $ligne->designation }}</td>
                                <td class="p-2 text-center border border-gray-200 dark:border-gray-700" title="{{ formatNumber($ligne->quantite).($ligne->matiere ? ' '.$ligne->matiere->unite->full : '') }}">
                                    {{ formatNumber($ligne->quantite).($ligne->matiere ? ' '.$ligne->matiere->unite->short : '') }}</td>
                                <td class="p-2 text-left border border-gray-200 dark:border-gray-700"
                                    title="{{ formatNumberArgent($ligne->prix_unitaire) }} {{ $ligne->matiere ? 'euro(s) par'.$ligne->matiere->unite->full : '' }}">
                                    {{ formatNumberArgent($ligne->prix_unitaire) }}{{ $ligne->matiere ? '/'.$ligne->matiere->unite->short : '' }}
                                </td>
                                <td class="p-2 text-left border border-gray-200 dark:border-gray-700">
                                    {{ formatNumberArgent($ligne->prix) }} </td>
                                <td class="p-2 text-left border border-gray-200 dark:border-gray-700">
                                    {{ Carbon::parse($ligne->date_livraison)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                        <tr class="border-t-2 border-gray-200 dark:border-gray-700">
                            <td class="p-2 " colspan="400">
                                <div class="w-full">

                                    <table class="min-w-0 float-right text-right">
                                        <tbody>
                                            <tr
                                                class="{{ $cde->frais_de_port || $cde->frais_divers ? '' : 'hidden' }}">
                                                <td class="pr-4 text-gray-500">
                                                    Total HT :
                                                </td>
                                                <td id="total_ht_gray" class="text-gray-500">
                                                    {{ formatNumberArgent($cde->total_ht) }}
                                                </td>
                                            </tr>
                                            <tr class="{{ $cde->frais_de_port ? '' : 'hidden' }}">
                                                <td class="pr-4 text-gray-500">
                                                    Frais de port :
                                                </td>
                                                <td id="frais_de_port" class="text-gray-500">
                                                    {{ formatNumberArgent($cde->frais_de_port) }}
                                                </td>
                                            </tr>
                                            <tr class="{{ $cde->frais_divers ? '' : 'hidden' }}">
                                                <td class="pr-4 text-gray-500">
                                                    Frais divers :
                                                </td>
                                                <td id="frais_divers" class="text-gray-500">
                                                    {{ formatNumberArgent($cde->frais_divers) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pr-4">
                                                    Total HT :
                                                </td>
                                                <td id="total_ht">
                                                    {{ formatNumberArgent($cde->total_ht + $cde->frais_de_port + $cde->frais_divers) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pr-4" id="tva_container">
                                                    TVA ({{ $cde->tva }}%) :
                                                </td>
                                                <td id="total_tva_plus">
                                                    {{ formatNumberArgent(round((($cde->total_ht + $cde->frais_de_port + $cde->frais_divers) * $cde->tva) / 100, 3)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pr-4">
                                                    Total TTC :
                                                </td>
                                                <td id="total_ttc">
                                                    {{ formatNumberArgent(round($cde->total_ht + $cde->frais_de_port + $cde->frais_divers + (($cde->total_ht + $cde->frais_de_port + $cde->frais_divers) * $cde->tva) / 100, 3)) }}

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{--
########  #### ######## ########       ########  ########      ########     ###     ######   ########
##     ##  ##  ##       ##     ##      ##     ## ##            ##     ##   ## ##   ##    ##  ##
##     ##  ##  ##       ##     ##      ##     ## ##            ##     ##  ##   ##  ##        ##
########   ##  ######   ##     ##      ##     ## ######        ########  ##     ## ##   #### ######
##         ##  ##       ##     ##      ##     ## ##            ##        ######### ##    ##  ##
##         ##  ##       ##     ##      ##     ## ##            ##        ##     ## ##    ##  ##
##        #### ######## ########       ########  ########      ##        ##     ##  ######   ########
--}}
                <h2 class="text-xl font-bold mb-6 text-left border-b-2 border-gray-200 dark:border-gray-700 p-2">Pied
                    de page</h2>
                <div class="flex ">
                    <div class="flex flex-col gap-4 m-4">
                        <x-input-label value="type d'expédition" />
                        <select name="type_expedition_id" required class="select w-fit min-w-96"
                            onchange="changeTypeExpedition(this)">
                            @foreach ($typesExpedition as $typeExpedition)
                                <option value="{{ $typeExpedition->id }}"
                                    {{ old('type_expedition_id', $cde->type_expedition_id) == $typeExpedition->id ? 'selected' : '' }}>
                                    {{ $typeExpedition->nom }}
                                </option>
                            @endforeach
                        </select>
                        @php
                            if ($cde->adresse_livraison == null) {
                                $adresse_livraison = new stdClass();
                                $adresse_livraison->adresse = $entite->adresse;
                                $adresse_livraison->ville = $entite->ville;
                                $adresse_livraison->code_postal = $entite->code_postal;
                                $adresse_livraison->pays = 'France';
                                $adresse_livraison->horaires = $entite->horaires;
                            } else {
                                $adresse_livraison = json_decode($cde->adresse_livraison);
                            }
                        @endphp
                        <div class="flex flex-col gap-4 m-4 ml-0" id="adresse_livraison">
                            <div>
                                <x-input-label value="horaires de livraison" />
                                <textarea name="horaires"
                                    class="mt-1 block px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100 w-fit min-w-96">{{ old('horaires', $adresse_livraison->horaires) }}</textarea>
                                @error('horaires')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label value="Adresse de livraison" />
                                <x-text-input name="adresse" :value="old('adresse', $adresse_livraison->adresse)" class="w-fit min-w-96" />
                                @error('adresse')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label value="Ville" />
                                <x-text-input name="ville" :value="old('ville', $adresse_livraison->ville)" />
                                @error('ville')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label value="Code Postal" />
                                <x-text-input name="code_postal" :value="old('code_postal', $adresse_livraison->code_postal)" />
                                @error('code_postal')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label value="Pays" />
                                <x-text-input name="pays" :value="old('pays', $adresse_livraison->pays)" />
                                @error('pays')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-col gap-4 m-4">
                            <x-input-label value="Conditions de paiement" />
                            <div>
                                <select name="condition_paiement_id" required class="select w-fit min-w-96"
                                    onchange="changeConditionPaiement()">

                                    @foreach ($conditionsPaiement as $conditionPaiement)
                                        <option value="{{ $conditionPaiement->id }}"
                                            {{ old(
                                                'condition_paiement_id',
                                                $cde->condition_paiement_id != null
                                                    ? ($cde->condition_paiement_id == $conditionPaiement->id
                                                        ? 'selected'
                                                        : '')
                                                    : ($cde->societeContact->etablissement->societe->condition_paiement_id == $conditionPaiement->id
                                                        ? 'selected'
                                                        : ''),
                                            ) }}>
                                            {{ $conditionPaiement->nom }}
                                        </option>
                                    @endforeach
                                    <option value="0">Autre</option>
                                </select>
                                <x-text-input name="condition_paiement_text" :value="old('condition_paiement_text')"
                                    class="w-fit min-w-96 rounded-t-none border-0 pt-2 -mt-2 hidden focus:border-t-0 focus:ring-0" />
                                <script>
                                    function changeConditionPaiement() {
                                        const select = document.querySelector('select[name="condition_paiement_id"]');
                                        const input = document.querySelector('input[name="condition_paiement_text"]');
                                        if (select.value == 0) {
                                            input.classList.remove('hidden');
                                            input.required = true;
                                            input.focus();
                                        } else {
                                            input.classList.add('hidden');
                                            input.value = '';
                                            input.required = false;
                                        }
                                    }
                                </script>
                            </div>
                            @error('condition_paiement_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-4">
                    <a href="{{ route('cde.show', $cde->id) }}" class="btn">{{ __('Retour') }}</a>
                    <button type="submit" class="btn">{{ __('Valider') }}</button>
                </div>
            </div>
    {{--
 ######  ##     ##    ###    ##    ##  ######   ######## ##     ## ######## ##    ## ########
##    ## ##     ##   ## ##   ###   ## ##    ##  ##       ###   ### ##       ###   ##    ##
##       ##     ##  ##   ##  ####  ## ##        ##       #### #### ##       ####  ##    ##
##       ######### ##     ## ## ## ## ##   #### ######   ## ### ## ######   ## ## ##    ##
##       ##     ## ######### ##  #### ##    ##  ##       ##     ## ##       ##  ####    ##
##    ## ##     ## ##     ## ##   ### ##    ##  ##       ##     ## ##       ##   ###    ##
 ######  ##     ## ##     ## ##    ##  ######   ######## ##     ## ######## ##    ##    ##



########  ########      ########  ######## ########
##     ## ##            ##     ## ##       ##
##     ## ##            ##     ## ##       ##
##     ## ######        ########  ######   ######
##     ## ##            ##   ##   ##       ##
##     ## ##            ##    ##  ##       ##
########  ########      ##     ## ######## ##

--}}

            @if ($listeChangement != false)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('open-modal-listeChangement').click();
                });
            </script>
            <x-modal name="listeChangement-modal" id="listeChangement-modal" title="Liste des changements" maxWidth="5xl">
                <div class="p-2">
                    <a x-on:click="$dispatch('close')">
                        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                    </a>
                    <div class="p-6 ">
                        <div class="flex flex-col gap-4">
                            <h2
                                class="text-xl font-bold mb-6 text-left border-b-2 border-gray-200 dark:border-gray-700 p-2">
                                Ces références ont changé, voulez-vous les enregistrer ou les garder seulement pour cette commande ?</h2>
                            <table class="min-w-0 bg-gray-100 dark:bg-gray-900 ">
                                <thead>
                                    <tr>
                                        <th class="text-left">Ref Interne</th>
                                        <th class="text-left">Désignation</th>
                                        <th class="text-left">Changement</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listeChangement as $changement)
                                        <tr>
                                            <td class="p-2 text-left">{{ $changement['ref_interne'] }}</td>
                                            <td class="p-2 text-left">{{ $changement['designation'] }}</td>
                                            <td class="p-2 text-left flex items-center">
                                                <span title="Ancienne référence">{{ $changement['ref_externe'] }}</span>
                                                <x-icon size="1" type="arrow_forward" class="icons-no_hover" />
                                                <span title="Nouvelle référence">{{ $changement['ref_fournisseur'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="flex justify-end mt-4">
                                <x-toggle :checked="old('enregistrer_changement', true)"
                                    :label="'Enregistrer les changements ?'" id="enregistrer_changement"
                                    name="enregistrer_changement" class="toggle-class" />
                                @error('enregistrer_changement')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" x-on:click="$dispatch('close')"
                                class="btn">{{ __('Fermer') }}</button>
                        </div>
                    </div>
            </x-modal>
        @endif
        </form>
    </div>






    <script>
        function recalculateTotal() {
            const totalHtElement = document.getElementById('total_ht');
            const tvatext = document.getElementById('tva_container');
            const tvaElement = document.getElementById('total_tva_plus');
            const totalTtcElement = document.getElementById('total_ttc');
            const tvaInput = document.querySelector('input[name="tva"]');
            const frais_de_portInput = document.querySelector('input[name="frais_de_port"]');
            const frais_diversInput = document.querySelector('input[name="frais_divers"]');
            const total_ht_grayElement = document.getElementById('total_ht_gray');
            const frais_de_portElement = document.getElementById('frais_de_port');
            const frais_diversElement = document.getElementById('frais_divers');
            const total_ht_gray = parseFloat(@json($cde->total_ht)) || 0;
            const frais_de_port = parseFloat(frais_de_portInput.value) || 0;
            const frais_divers = parseFloat(frais_diversInput.value) || 0;
            const totalHt = parseFloat(@json($cde->total_ht)) + frais_de_port + frais_divers;
            const tva = parseFloat(tvaInput.value) || 0;
            const tvaAmount = (totalHt * tva / 100);
            const totalTtc = totalHt + tvaAmount;
            if (frais_de_port > 0) {
                frais_de_portElement.parentElement.classList.remove('hidden');
            } else {
                frais_de_portElement.parentElement.classList.add('hidden');
            }
            if (frais_divers > 0) {
                frais_diversElement.parentElement.classList.remove('hidden');
            } else {
                frais_diversElement.parentElement.classList.add('hidden');
            }
            frais_de_portElement.textContent = frais_de_port.toFixed(2) + ' €';
            frais_diversElement.textContent = frais_divers.toFixed(2) + ' €';
            total_ht_grayElement.textContent = total_ht_gray.toFixed(2) + ' €';
            tvatext.textContent = 'TVA (' + tva + '%) :';
            tvaElement.textContent = tvaAmount.toFixed(2) + ' €';
            totalHtElement.textContent = totalHt.toFixed(2) + ' €';
            totalTtcElement.textContent = totalTtc.toFixed(2) + ' €';
        };

        function changeTypeExpedition(select) {
            const adresse_livraison = document.getElementById('adresse_livraison');
            const typeExpedition = select.value;
            if (typeExpedition == 1) {
                adresse_livraison.classList.remove('hidden');
            } else {
                adresse_livraison.classList.add('hidden');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const typeExpedition = document.querySelector('select[name="type_expedition_id"]');
            changeTypeExpedition(typeExpedition);
            changeConditionPaiement();
        });
    </script>


</x-app-layout>
