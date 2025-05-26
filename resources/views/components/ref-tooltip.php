<x-tooltip :position="'right'" :class="'group'">
    <x-slot:slot_item>
        <span class="cursor-pointer underline">
            {{ $matiere->ref_interne }}
        </span>
    </x-slot:slot_item>
    <x-slot:slot_tooltip>
        @if ($matiere->quantite() > 1)
        <h3 class="text-sm font-semibold mb-2 whitespace-nowrap border-b">{{ $matiere->quantite() }}
            {{ $matiere->unite->full_plural }}
        </h3>
        @else
        <h3 class="text-sm font-semibold mb-2 whitespace-nowrap border-b">{{ $matiere->quantite() }}
            {{ $matiere->unite->full }}
        </h3>
        @endif
        <ul class="text-sm space-y-1">
            @foreach ($matiere->stock->where("quantite",'!=',0) as $stock)
            <li>- {{ formatNumber($stock->quantite) }} x {{ formatNumber($stock->valeur_unitaire) }} {{ $matiere->unite->short }}</li>
            @endforeach
        </ul>
    </x-slot:slot_tooltip>
</x-tooltip>
