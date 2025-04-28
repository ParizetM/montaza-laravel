{{-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\components\changements_stock.blade.php --}}
<div class="fixed top-1/2 right-0 transform -translate-y-1/2" x-data>
    <button
        @click="$dispatch('open-volet', 'changements-stock')"
        class="btn-select-left px-2 py-8 -mr-4 bg-gray-200 dark:bg-gray-800 shadow-lg hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700">
        <x-icon :size="2" type="arrow_back" />
    </button>

</div>
<x-volet-modal name="changements-stock" direction="right" x-init="$dispatch('open-volet', 'changements-stock')">
    <div class="p-2 text-gray-900 dark:text-gray-100">
        <a @click="$dispatch('close')">
            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
        </a>

        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4 text-center">Mouvements de stock</h2>

            <table class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <!-- En-tête -->
                <thead class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raison</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                    </tr>
                </thead>

                <!-- Corps du tableau -->
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @if (empty($mouvements_stock) || count($mouvements_stock) === 0)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100" colspan="7">
                                Aucun mouvement de stock n'a été enregistré.
                            </td>
                        </tr>
                    @else
                        @foreach ($mouvements_stock as $mouvement)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $mouvement->matiere->nom ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $mouvement->quantite }} {{ $mouvement->matiere->unite ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ number_format($mouvement->valeur_unitaire, 2) }} €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ number_format($mouvement->quantite * $mouvement->valeur_unitaire, 2) }} €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $mouvement->raison }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($mouvement->date)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $mouvement->user->name ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>

                <!-- Pied de tableau -->
                <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="3">Total</td>
                        <td class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100">
                            @if (!empty($mouvements_stock) && count($mouvements_stock) > 0)
                                {{ number_format($mouvements_stock->sum(fn($m) => $m->quantite * $m->valeur_unitaire), 2) }} €
                            @else
                                0.00 €
                            @endif
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-volet-modal>
