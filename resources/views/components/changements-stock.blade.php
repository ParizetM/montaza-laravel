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
    <div class="text-gray-900 dark:text-gray-100 h-full flex flex-col">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold">Mouvements de stock</h2>
            <button @click="$dispatch('close')" class="hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full p-1 transition-colors">
                <x-icons.close size="1.5" unfocus />
            </button>
        </div>

        <div class="flex-1 p-4 overflow-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="text-left px-4 py-2 bg-gray-50 dark:bg-gray-800 font-medium text-sm">Matière</th>
                        <th class="text-left px-4 py-2 bg-gray-50 dark:bg-gray-800 font-medium text-sm">Mouvement</th>
                        <th class="text-left px-4 py-2 bg-gray-50 dark:bg-gray-800 font-medium text-sm">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($changements_stock as $mouvement)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-2">
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
                        <td class="px-4 py-2 whitespace-nowrap">
                            @if ($mouvement->type_mouvement == 'entree')
                                <span class="text-red-500">- {{ $mouvement->quantite }}</span>
                                @if ($mouvement->valeur_unitaire != null)
                                    <span class="text-gray-600 dark:text-gray-400">
                                        × {{ formatNumber($mouvement->valeur_unitaire) . ' ' . $mouvement->matiere->unite->short }}
                                    </span>
                                @endif
                            @else
                                <span class="text-green-500">+ {{ $mouvement->quantite }}</span>
                                @if ($mouvement->valeur_unitaire != null)
                                    <span class="text-gray-600 dark:text-gray-400">
                                        × {{ formatNumber($mouvement->valeur_unitaire) . ' ' . $mouvement->matiere->unite->short }}
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-gray-600 dark:text-gray-400">
                            {{ $mouvement->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                    @if ($changements_stock->isEmpty())
                    <tr>
                        <td colspan="3" class="py-6 text-center text-gray-500 dark:text-gray-400">
                            Aucun mouvement de stock trouvé.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-volet-modal>
