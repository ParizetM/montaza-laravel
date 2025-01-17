<x-app-layout>
    <x-slot name="header">
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
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-6 text-left">{{ $ddp->nom }} - Récapitulatif</h1>
                <a href="{{ route('ddp.pdfs.download', $ddp) }}" class="btn">Télécharger tous les PDF</a>

            </div>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="bg-white dark:bg-gray-800"></th>
                        <th colspan="9" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fournisseurs</th>
                    </tr>
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matières</th>
                        @foreach ($ddp_societes as $societe)
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-white dark:bg-gray-800">{{ $societe->raison_sociale }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @for ($i = 1; $i <= 23; $i++)
                        <tr class="">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center bg-white dark:bg-gray-800">Matière {{ $i }}</td>
                            @foreach ($ddp_societes as $societe)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center bg-gray-100 dark:bg-gray-900">
                                    <x-text-input name="matiere_{{ $i }}_{{ $societe->id }}" type="text" class="w-20" />
                                    <x-date-input name="date_{{ $i }}_{{ $societe->id }}" class="w-20" />
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-sm text-gray-900 dark:text-gray-100 text-center">Total</td>
                        @foreach ($ddp_societes as $societe)
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-sm text-gray-900 dark:text-gray-100 text-center bg-gray-100 dark:bg-gray-900">23 €</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>




</x-app-layout>
