<x-app-layout>
    @section('title', 'Récapitulatif - ' . $ddp->code)
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
            <div class="flex items-center mb-12">
                <h1 class="text-3xl font-bold  text-left mr-2">{{ $ddp->nom }} - Récapitulatif</h1>
                <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                    style="background-color: {{ $ddp->statut->couleur }}; color: {{ $ddp->statut->couleur_texte }}">
                    {{ $ddp->statut->nom }}</div>
            </div>
            <div class="overflow-x-auto overflow-y-visible">
                <table class="w-auto table-auto bg-white dark:bg-gray-900 min-w-0">
                    <thead class="">
                        <tr class="bg-gray-200 dark:bg-gray-700 border-r-2 border-r-gray-200 dark:border-r-gray-700">
                            <th class=" p-2 text-center"></th>
                            <th class=" p-2 text-center"></th>
                            @foreach ($ddp_societes as $societe)
                                <th colspan="3"
                                    class=" p-2 text-center border-l-2 border-l-gray-500 dark:border-l-gray-300">
                                    {{ $societe->raison_sociale }}</th>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-200 dark:bg-gray-700 border-r-2 border-r-gray-200 dark:border-r-gray-700">
                            <th colspan="1" class=" p-2 text-center">
                                Matière</th>
                            <th colspan="1" class=" p-2 text-center">
                                quantité</th>
                            @foreach ($ddp_societes as $societe)
                                <th class=" p-2 text-center border-l-2 border-l-gray-500 dark:border-l-gray-300">
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
                                    <td class="text-center border border-gray-300 dark:border-gray-700"> TOTAL</td>
                                    <td class="text-center border border-gray-300 dark:border-gray-700">
                                        {{ $total_quantite }}</td>
                                @else
                                    <td
                                        class="border border-gray-300 dark:border-gray-700 pl-2
                                            {{ $index % 2 == 1 ? 'bg-gray-50 dark:bg-gray-800' : '' }}
                                    ">
                                        {{ $ddplignes[$index]->matiere->designation }}</td>
                                    <td
                                        class="text-center border border-gray-300 dark:border-gray-700
                                            {{ $index % 2 == 1 ? 'bg-gray-50 dark:bg-gray-800' : '' }}
                                    ">
                                        {{ formatNumber($ddplignes[$index]->quantite) }}</td>
                                @endif

                                @foreach ($ligne as $key => $value)
                                    @if ($value == 'UNDEFINED')
                                        <td
                                            class="border border-gray-300 dark:border-gray-700 p-2 bg-gray-200 dark:bg-gray-1000 {{ $key % 3 == 0 ? 'border-l-2 border-l-gray-500 dark:border-l-gray-300' : '' }}">
                                        </td>
                                    @else
                                        <td
                                            class="border border-gray-300 dark:border-gray-700 p-2 {{ $key % 3 == 0 ? 'border-l-2 border-l-gray-500 dark:border-l-gray-300' : '' }} {{ $index % 2 == 1 ? 'bg-gray-50 dark:bg-gray-800' : '' }} whitespace-nowrap
                                        ">
                                            {{ $value }}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="dark:bg-gray-800 border-r-2 border-r-white dark:border-r-gray-800">
                            <td colspan="2"></td>
                            @foreach ($ddp_societe_contacts as $societe_contact)
                                <td colspan="3" class="">
                                    <a href="{{ route('ddp.commander', ['ddp' => $ddp->id, 'societe_contact' => $societe_contact->id]) }}"
                                        class=" btn-select-bottom-right btn-select-bottom-left text-center mb-10 dark:bg-gray-700 dark:hover:bg-gray-600" title="Commander chez {{ $societe->raison_sociale }}">Commander</a>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('ddp.annuler_terminer', $ddp->id) }}" class="btn float-right">Retour</a>
                <a href="{{ route('ddp.terminer', $ddp->id) }}" class="btn float-right">Terminer</a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let minValue = Infinity;
                let minCells = [];

                cells.forEach((cell, index) => {
                    if (index % 3 === 0) { // Only look at every 3rd column
                        const value = parseFloat(cell.textContent.trim().replace(/,/g, ''));
                        if (!isNaN(value)) {
                            if (value < minValue) {
                                minValue = value;
                                minCells = [cell];
                            } else if (value === minValue) {
                                minCells.push(cell);
                            }
                        }
                    }
                });

                minCells.forEach(cell => {
                    cell.style.color = 'green';
                });
            });
        });
    </script>


</x-app-layout>
