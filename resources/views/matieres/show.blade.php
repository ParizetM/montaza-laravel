<x-app-layout>
    <x-slot name="header">
        <div>
            {{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                <a href="{{ route('ddp.show', $ddp->id) }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{!! __('Créer une demande de prix') !!}</a>
                >> Validation
            </h2> --}}
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $matiere->designation }}</h1>

            </div>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Référence Interne</th>
                        <th class="px-4 py-2">Sous Famille</th>
                        <th class="px-4 py-2">Désignation</th>
                        <th class="px-4 py-2">Standard</th>
                        <th class="px-4 py-2">DN</th>
                        <th class="px-4 py-2">Épaisseur</th>
                        <th class="px-4 py-2">Unité</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">{{ $matiere->ref_interne }}</td>
                        <td class="border px-4 py-2">{{ $matiere->sousFamille->nom }}</td>
                        <td class="border px-4 py-2">{{ $matiere->designation }}</td>
                        <td class="border px-4 py-2 flex items-center">
                            <x-icons.pdf class="w-6 h-6" />
                            <a href="{{ $matiere->standardVersion->chemin_pdf }}" class="lien" target="_blank">
                                {{ $matiere->standardVersion->standard->nom ?? '-' }} -
                                {{ $matiere->standardVersion->version ?? '-' }}
                            </a>
                        </td>
                        <td class="border px-4 py-2">{{ $matiere->dn }}</td>
                        <td class="border px-4 py-2">{{ $matiere->epaisseur }}</td>
                        <td class="border px-4 py-2" title="{{ $matiere->unite->full }}">{{ $matiere->unite->short }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="mt-6 w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Fournisseur</th>
                        <th class="px-4 py-2">Dernier prix </th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fournisseurs_dernier_prix as $fournisseur)
                        <tr onclick="window.location.href = '{{ route('matieres.show_prix',['matiere' => $matiere->id,'fournisseur' => $fournisseur->id]) }}';"
                            class="hover:bg-gray-100 hover:dark:bg-gray-700 cursor-pointer">
                            <td class="border px-4 py-2 whitespace-nowrap">{{ $fournisseur->raison_sociale }}</td>
                            <td class="border px-4 py-2 whitespace-nowrap">{{ $fournisseur->pivot->prix }} €</td>
                            <td class="border px-4 py-2 whitespace-nowrap">{{ $fournisseur->pivot->date_dernier_prix }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>




</x-app-layout>
