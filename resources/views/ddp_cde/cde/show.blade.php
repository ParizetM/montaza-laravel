<x-app-layout>
    @section('title', 'Commande - ' . $cde->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('cde.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Commandes</a>
                    >>
                    <a href="{{ route('cde.show', $cde->id) }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une commande') !!}</a>
                    >>
                    <a href="{{ route('cde.annuler_terminer', $cde->id) }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Retours') !!}</a>
                    >> Récapitulatif
                </h2>

            </div>
            <a href="{{ route('cde.pdfs.download', $cde) }}" class="btn">Télécharger le PDF</a>
            <a href="{{ route('cde.pdfs.pdfdownload_sans_prix', $cde) }}" class="btn">Télécharger le PDF sans
                prix</a>
            <a href="{{ route('cde.annuler', $cde->id) }}" class="btn">Annuler la commande</a>
        </div>
    </x-slot>
    {{--
##     ##  #######  ##       ######## ########       ######  ########  #######   ######  ##     ##
##     ## ##     ## ##       ##          ##         ##    ##    ##    ##     ## ##    ## ##    ##
##     ## ##     ## ##       ##          ##         ##          ##    ##     ## ##       ##   ##
##     ## ##     ## ##       ######      ##          ######     ##    ##     ## ##       #####
 ##   ##  ##     ## ##       ##          ##               ##    ##    ##     ## ##       ##   ##
  ## ##   ##     ## ##       ##          ##         ##    ##    ##    ##     ## ##    ## ##    ##
   ###     #######  ######## ########    ##          ######     ##     #######   ######  ##     ##

 --}}

    <div class="fixed top-1/2 right-0 transform -translate-y-1/2" x-data>
        <button @click="$dispatch('open-volet', 'changements-stock')"
            class="btn-select-left flex items-center px-2 py-8 bg-gray-200 dark:bg-gray-800 shadow-lg hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700">
            <x-icon :size="1" type="arrow_back" />
            <span class=" whitespace-nowrap font-medium transform -rotate-90 inline-block w-1 mt-30 -mb-7">Changements
                stock</span>
        </button>

    </div>
    @include('ddp_cde.cde.partials.enregistrement_stock')

    {{-- @else
            <x-changements-stock :changements_stock="$changements_stock" />
    @endif --}}
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md ">
            <div class="flex items-center mb-12">
                <h1 class="text-3xl font-bold  text-left mr-2">{{ $cde->nom }} - Récapitulatif</h1>
                <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                    style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                    {{ $cde->statut->nom }}</div>
            </div>
            <div class="overflow-x-auto overflow-y-visible">
                <div class="float-left">
                    <table class="w-auto table-auto bg-white dark:bg-gray-900 min-w-0">
                        <thead class="">
                            <tr
                                class="bg-gray-200 dark:bg-gray-700 border-r-2 border-r-gray-200 dark:border-r-gray-700">
                                <th style="width: 5px; padding: 0%;padding-top:5px;">
                                    <div class="poste">Poste</div>
                                </th>
                                <th colspan="2" class=" p-2 text-center">
                                    Matière</th>
                                <th colspan="1" class=" p-2 text-center"> Quantité</th>
                                <th colspan="1" class=" p-2 text-center"> PU HT</th>
                                <th colspan="1" class=" p-2 text-center"> Montant HT</th>
                                <th colspan="1" class=" p-2 text-center"> Type d&#39;expedition</th>
                                <th colspan="1" class=" p-2 text-center"> Date de livraison</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cde->cdeLignes as $ligne)
                                @if ($ligne->ddpCdeStatut->nom == 'Annulée')
                                    <tr class="bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400">
                                        <td class="text-center ml-1 p-2">
                                            {{ $ligne->poste }}
                                        </td>
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

                                        <td class="p-2 text-left"><span
                                                class="text-red-500 dark:text-red-400 font-bold">Annulée </span>
                                            <span class="line-through">{{ $ligne->designation }}</span>
                                        </td>
                                        <td class="p-2 text-right line-through whitespace-nowrap"
                                            title="{{ formatNumber($ligne->quantite) }} {{ $ligne->matiere->unite->full }}">
                                            {{ formatNumber($ligne->quantite) }} {{ $ligne->matiere->unite->short }}
                                        </td>
                                        <td class="p-2 text-center line-through whitespace-nowrap">
                                            {{ formatNumberArgent($ligne->prix_unitaire) }}
                                        </td>
                                        <td class="p-2 text-center line-through whitespace-nowrap">
                                            {{ formatNumberArgent($ligne->prix) }}</td>
                                        <td class="p-2 text-center line-through">{{ $ligne->typeExpedition->short }}
                                        </td>
                                        <td class="p-2">
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center ml-1 p-2">
                                            {{ $ligne->poste }}
                                        </td>
                                        @if ($ligne->ligne_autre_id == null)
                                            <td class="text-left ml-1 p-2">
                                                <x-tooltip position="top">
                                                    <x-slot name="slot_tooltip">
                                                        <a href="{{ route('matieres.show', $ligne->matiere->id) }}"
                                                            target="_blank"
                                                            class="lien">{{ $ligne->matiere->designation }}</a>
                                                    </x-slot>
                                                    <x-slot name="slot_item">
                                                        <div class="flex flex-col {{ $showRefFournisseur ? '' : 'hidden' }}"
                                                            id="refs-{{ $ligne->matiere_id }}">
                                                            <div class="flex flex-col">
                                                                <span class="text-xs">Réf. Interne</span>
                                                                <span
                                                                    class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                                            </div>
                                                            <div class="flex flex-col">
                                                                <span class="text-xs">Réf. Fournisseur</span>
                                                                <span
                                                                    class="font-bold">{{ $ligne->ref_fournisseur ?? '-' }}</span>

                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col {{ $showRefFournisseur ? 'hidden' : '' }}"
                                                            id="ref-{{ $ligne->matiere_id }}">
                                                            <div class="flex flex-col">
                                                                <span class="text-xs">Réf. Interne</span>
                                                                <span
                                                                    class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </x-slot>
                                                </x-tooltip>
                                            </td>
                                        @else
                                            <td class="text-left ml-1 p-2">
                                                <div class="flex flex-col {{ $showRefFournisseur ? '' : 'hidden' }}"
                                                    id="refs-{{ $ligne->ligne_autre_id }}">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs">Réf. Interne</span>
                                                        <span class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs">Réf. Fournisseur</span>
                                                        <span
                                                            class="font-bold">{{ $ligne->ref_fournisseur ?? '-' }}</span>

                                                    </div>
                                                </div>
                                                <div class="flex flex-col {{ $showRefFournisseur ? 'hidden' : '' }}"
                                                    id="ref-{{ $ligne->ligne_autre_id }}">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs">Réf. Interne</span>
                                                        <span
                                                            class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="p-2 text-left">{{ $ligne->designation }}</td>
                                        <td class="p-2 text-right"
                                            title="{{ formatNumber($ligne->quantite) }} {{ $ligne->matiere ? $ligne->matiere->unite->full : '' }}">
                                            {{ formatNumber($ligne->quantite) }}
                                            {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}</td>
                                        <td class="p-2 text-center whitespace-nowrap">
                                            {{ formatNumberArgent($ligne->prix_unitaire) }}
                                        </td>
                                        <td class="p-2 text-center whitespace-nowrap">
                                            {{ formatNumberArgent($ligne->prix) }}</td>
                                        <td class="p-2 text-center">{{ $ligne->typeExpedition->short }}</td>
                                        <td class="p-2 text-center">
                                            {{ $ligne->date_livraison_reelle ? \Carbon\Carbon::parse($ligne->date_livraison_reelle)->format('d/m/Y') : 'Non livré' }}
                                        </td>
                                    </tr>
                                @endif
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
                                                        {{ formatNumberArgent($cde->total_ht - $cde->frais_de_port - $cde->frais_divers) }}
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
                                                        {{ formatNumberArgent($cde->total_ht) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="pr-4" id="tva_container">
                                                        TVA ({{ $cde->tva }}%) :
                                                    </td>
                                                    <td id="total_tva_plus">
                                                        {{ formatNumberArgent(($cde->total_ht * $cde->tva) / 100) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="pr-4">
                                                        Total TTC :
                                                    </td>
                                                    <td id="total_ttc">
                                                        {{ formatNumberArgent($cde->total_ttc) }}

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                </tbody>
                </table>
            </div>


        </div>
        {{-- Affichage des changements de livraison --}}
        <div class="mt-4 ml-2">
        @include('ddp_cde.cde.partials.changement_livraison')
        </div>
        {{-- Affichage des commentaires --}}
        <div class="mt-4 w-full md:w-5/6">
            @include('ddp_cde.cde.partials.commentaire')
        </div>
        <div class="flex justify-between items-center mt-6">
            @if ($cde->statut->id == 3)
                <a href="{{ route('cde.annuler_terminer', $cde->id) }}" class="btn float-right">annuler terminé</a>
                <a href="{{ route('cde.terminer_controler', $cde->id) }}" class="btn float-right">Terminer et
                    controlé</a>
            @elseif ($cde->statut->id == 5)
                <a href="{{ route('cde.annuler_terminer_controler', $cde->id) }}" class="btn float-right">Annuler
                    controlé</a>
            @endif

        </div>

    </div>
    </div>
    <div class="col-md-4">
        <livewire:media-sidebar :model="'cde'" :model-id="$cde->id" />
    </div>


</x-app-layout>
