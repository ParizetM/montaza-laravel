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
                    >> Validation
                </h2>

            </div>
            <a href="{{ route('ddp.pdfs.download', $ddp) }}" class="btn">Télécharger tous les PDF</a>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md ">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $ddp->nom }} - Récapitulatif</h1>
            </div>
            <div class="overflow-auto">
                <table class="w-full table-auto bg-white dark:bg-gray-900 ">
                    <thead>
                        <tr>
                            <th class=" p-2 text-center">
                                &nbsp;</th>
                            <th class=" p-2 text-center">
                                &nbsp;</th>
                            @foreach ($ddp_societes as $societe)
                                <th colspan="3"
                                    class=" p-2 text-center border-l-2 border-l-gray-500 dark:border-l-gray-700">
                                    {{ $societe->raison_sociale }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th colspan="1" class=" p-2 text-center">
                                Matière</th>
                            <th colspan="1" class=" p-2 text-center">
                                quantité</th>
                            @foreach ($ddp_societes as $societe)
                                <th class=" p-2 text-center border-l-2 border-l-gray-500 dark:border-l-gray-700">
                                    Prix unitaire</th>
                                <th class=" p-2 text-center">
                                    Montant</th>
                                <th class=" p-2 text-center">
                                    Délai</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $lastindex = count($data) - 1;
                            $total_quantite = 0;
                            foreach ($ddplignes as $ddpligne) {
                                $total_quantite += $ddpligne->quantite;
                            }
                        @endphp
                        @foreach ($data as $index => $ligne)
                            <tr>
                                @if ($index == $lastindex)
                                    <td> total</td>
                                    <td class="text-center">{{ $total_quantite }}</td>
                                @else
                                    <td>{{ $ddplignes[$index]->matiere->designation }}</td>
                                    <td class="text-center">{{ $ddplignes[$index]->quantite }}</td>
                                @endif

                                @foreach ($ligne as $key => $value)
                                    @if ($value == 'UNDEFINED')
                                        <td
                                            class="border border-gray-300 dark:border-gray-700 p-2 bg-gray-300 dark:bg-gray-700 {{ $key % 3 == 0 ? 'border-l-2 border-l-gray-500 dark:border-l-gray-700' : '' }}">
                                        </td>
                                    @else
                                        <td
                                            class="border border-gray-300 dark:border-gray-700 p-2 {{ $key % 3 == 0 ? 'border-l-2 border-l-gray-500 dark:border-l-gray-700' : '' }} whitespace-nowrap
                                        ">
                                            {{ $value }}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('ddp.annuler_terminer', $ddp->id) }}" class="btn float-right">Retour</a>
                <a href="{{ route('ddp.terminer', $ddp->id) }}" class="btn float-right">Terminer</a>
            </div>
        </div>
    </div>



</x-app-layout>
