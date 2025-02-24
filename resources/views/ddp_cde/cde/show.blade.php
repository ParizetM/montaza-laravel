<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('ddp_cde.index') }}"
                        class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                    >>
                    <a href="{{ route('cde.show', $cde->id) }}"
                        class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{!! __('Créer une demande de prix') !!}</a>
                    >> Validation
                </h2>

            </div>
            <a href="{{ route('cde.pdfs.download', $cde) }}" class="btn">Télécharger tous les PDF</a>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md ">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $cde->nom }} - Récapitulatif</h1>
            </div>
            <div class="overflow-x-auto overflow-y-visible">
                <div class="float-left">
                    <table class="w-auto table-auto bg-white dark:bg-gray-900 min-w-0">
                        <thead class="">
                            <tr
                                class="bg-gray-200 dark:bg-gray-700 border-r-2 border-r-gray-200 dark:border-r-gray-700">
                                <th colspan="2" class=" p-2 text-center">
                                    Matière</th>
                                <th colspan="1" class=" p-2 text-center">
                                    Statut</th>
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
                                    @else
                                    <tr>
                                @endif
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
                                <td class="p-2 text-left">{{ $ligne->designation }}</td>
                                @if ($ligne->ddpCdeStatut->nom == 'Annulée')
                                    <td class="p-2 text-center font-bold text-gray-600 dark:text-gray-300">
                                        {{ $ligne->ddpCdeStatut->nom }}</td>
                                @else
                                    <td class="p-2 text-center">{{ $ligne->ddpCdeStatut->nom }}</td>
                                @endif
                                <td class="p-2 text-right"
                                    title="{{ formatNumber($ligne->quantite) }} {{ $ligne->unite->full }}">
                                    {{ formatNumber($ligne->quantite) }} {{ $ligne->unite->short }}</td>
                                <td class="p-2 text-center">{{ formatNumberArgent($ligne->prix_unitaire) }}</td>
                                <td class="p-2 text-center">{{ formatNumberArgent($ligne->prix) }}</td>
                                <td class="p-2 text-center">{{ $ligne->typeExpedition->short }}</td>
                                <td class="p-2 text-center">
                                    {{ $ligne->date_livraison_reelle ? \Carbon\Carbon::parse($ligne->date_livraison_reelle)->format('d/m/Y') : '-' }}
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($cde->accuse_reception)
                    <div class="flex flex-wrap gap-4 float-right mr-12">
                        <div class="flex flex-col flex-wrap gap-4">
                            {{-- @dd($pdfs) --}}
                            @php
                                $pdf = $cde->accuse_reception;
                                $cdeannee = explode('-', $cde->code)[1];
                            @endphp
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
                @endif
            </div>
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('cde.annuler_terminer', $cde->id) }}" class="btn float-right">Retour</a>
                <a href="{{ route('cde.terminer_controler', $cde->id) }}" class="btn float-right">Terminer et
                    controlé</a>
            </div>
        </div>
    </div>



</x-app-layout>
