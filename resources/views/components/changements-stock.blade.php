{{-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\components\changements_stock.blade.php --}}
@props(['changements_stock'])
<div class="fixed top-1/2 right-0 transform -translate-y-1/2" x-data>
    <button @click="$dispatch('open-volet', 'changements-stock')"
        class="btn-select-left flex items-center px-2 py-8 bg-gray-200 dark:bg-gray-800 shadow-lg hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700">
        <x-icon :size="1" type="arrow_back" />
            <span class=" whitespace-nowrap font-medium transform -rotate-90 inline-block w-1 mt-30 -mb-7">Changements stock</span>
    </button>

</div>
<x-volet-modal name="changements-stock" direction="right" x-init="$dispatch('open-volet', 'changements-stock')">
    <div class="p-2 text-gray-900 dark:text-gray-100">
        <a @click="$dispatch('close')">
            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
        </a>

        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4 text-center">Mouvements de stock</h2>

        <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-700">
            <thead>
                <tr class="">
                    <th class=" px-4 py-2">Matière</th>
                    <th class=" px-4 py-2">Mouvement</th>
                    <th class=" px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($changements_stock as $mouvement)
                        <tr class="">
                            <td class="border px-4 py-2 border-gray-300 dark:border-gray-700 ">
                                <x-tooltip position="top">
                                    <x-slot name="slot_tooltip">
                                        <a href="{{ route('matieres.show', $mouvement->matiere_id) }}"
                                            target="_blank"
                                            class="lien">{{ $mouvement->matiere->designation }}</a>
                                    </x-slot>
                                    <x-slot name="slot_item">
                                    {{ $mouvement->matiere->designation }}
                                    </x-slot>
                                </x-tooltip>
                            </td>
                            <td class="border px-4 py-2 whitespace-nowrap border-gray-300 dark:border-gray-700 ">
                                @if ($mouvement->type_mouvement == 'entree')
                                    <span class="text-red-500">- {{ $mouvement->quantite }}</span>
                                    @if ($mouvement->valeur_unitaire != null)
                                        <span class="text-red-500">x ({{ formatNumber($mouvement->valeur_unitaire) . ' ' . $mouvement->matiere->unite->short }})</span>
                                    @endif
                                @else
                                    <span class="text-green-500">+ {{ $mouvement->quantite }}</span>
                                    @if ($mouvement->valeur_unitaire != null)
                                        <span class="text-green-500">x ({{ formatNumber($mouvement->valeur_unitaire) . ' ' . $mouvement->matiere->unite->short }})</span>
                                    @endif
                                @endif
                            </td>
                            <td class="border px-4 py-2 whitespace-nowrap border-gray-300 dark:border-gray-700 ">
                                {{ $mouvement->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
                @if ($changements_stock->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center py-4">Aucun mouvement de stock trouvé.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        </div>
    </div>
</x-volet-modal>
