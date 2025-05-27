<div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200">
    <div class="flex justify-between items-center">

        <h1 class="text-3xl font-bold mb-1">
            <a href="{{ route('cde.index') }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Commandes') }}
            </a>
        </h1>
        <a href="{{ route('cde.index') }}" class="btn mb-2">Voir tout</a>

    </div>

    <p class="text-lg mb-2">
        Commandes en cours
    </p>
    <table>
        <thead>
            <tr>
                <th class="px-4 py-2">Numéro</th>
                <th class="px-4 py-2">Date</th>
                @if (!$isSmall)
                    <th class="px-4 py-2"></th>
                @endif
                <th class="px-4 py-2">Nom</th>
                @if (!$isSmall)
                    <th class="px-4 py-2">Destinataire</th>
                @endif
                <th class="px-4 py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cdes as $cde)
                <tr " class="border-b border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700 cursor-pointer"
                onclick="window.location='{{ route('cde.show', $cde) }}'">
                    <!-- Code -->
                    <td class="min-w-2 text-sm">
                        <x-tooltip position="right">
                            <x-slot name="slot_item">
                                <div class="flex items-center">
                                    <span class="whitespace-nowrap">{{ $cde->code }}</span>
                                    <x-icons.cde size="1.2" class="ml-1 fill-gray-700 dark:fill-gray-300 border-b-2 border-gray-700 dark:border-gray-300" />
                                </div>
                            </x-slot>
                            <x-slot name="slot_tooltip">
                                <div class="flex flex-col max-w-md">
                                    <h3 class="text-gray-900 dark:text-gray-100 font-bold mb-2">
                                        Contenu de la commande
                                    </h3>
                                     @if ($cde->cdeLignes->count() > 0)
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="px-2 py-1 text-xs">Poste</th>
                                <th class="px-2 py-1 text-xs">Désignation</th>
                                <th class="px-2 py-1 text-xs">Qté</th>
                                <th class="px-2 py-1 text-xs">Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cde->cdeLignes as $ligne)
                                <tr
                                    class="border-b dark:border-gray-600  {{ $ligne->ddp_cde_statut_id == 4 || $ligne->date_livraison_reelle == null ? 'line-through' : '' }}">
                                    <td class="px-2 py-1 text-xs">{{ $ligne->poste }}</td>
                                    <td class="px-2 py-1 text-xs">{{ $ligne->designation }}</td>
                                    <td class="px-2 py-1 text-xs text-right whitespace-nowrap">
                                        {{ formatNumber($ligne->quantite) }}
                                        {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}</td>
                                    <td class="px-2 py-1 text-xs text-right whitespace-nowrap">
                                        {{ formatNumberArgent($ligne->prix) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-bold bg-gray-50 dark:bg-gray-800">
                                <td colspan="3" class="px-2 py-1 text-xs text-right">Total:</td>
                                <td class="px-2 py-1 text-xs text-right whitespace-nowrap">
                                    {{ formatNumberArgent($cde->total_ht) }}
                                </td>
                            </tr>
                            <tr class="font-bold bg-gray-50 dark:bg-gray-800">
                                <td colspan="3" class="px-2 py-1 text-xs text-right">Total TTC:</td>
                                <td class="px-2 py-1 text-xs text-right whitespace-nowrap">
                                    {{ formatNumberArgent($cde->total_ttc) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p class="text-gray-600 dark:text-gray-300">Aucune ligne dans cette commande</p>
            @endif
</div>
</x-slot>
</x-tooltip>
</td>

<!-- Date de création -->
<!-- Date de création -->
<td class="pl-2 text-xs leading-5 {{ $isSmall ? 'text-center' : 'text-right' }}">
    <span class="text-nowrap"><span class="pr-1 leading-5">{{ $cde->created_at->format('d/m/Y') }}</span>
</td>
@if (!$isSmall)
    <td class="pr-2 text-xs leading-5">
        <small>{{ $cde->updated_at->format('H:i') }}</small></span>
    </td>
@endif


<!-- Nom -->
<td>
    @if ($isSmall && Str::length($cde->nom) > 25)
    <x-tooltip position="top">
        <x-slot name="slot_item">
            {{ Str::limit($cde->nom, 25) }}
        </x-slot>
        <x-slot name="slot_tooltip">
            {{ $cde->nom }}
        </x-slot>
    </x-tooltip>
    @else
        {{ $cde->nom }}
    @endif
</td>
@if (!$isSmall)
    {{-- <td>
                    {{ $cde->user->first_name }} {{ $cde->user->last_name }}
                </td> --}}
    <td>
        <x-tooltip position="top">
            <x-slot name="slot_item">
                <span class="text-gray-900 dark:text-gray-100">
                    {{ $cde->societe->raison_sociale }}
                </span>

            </x-slot>
            <x-slot name="slot_tooltip">
                <div class="flex flex-col">
                    <h3 class="text-gray-900 dark:text-gray-100 font-bold">
                        Destinataire{{ $cde->societeContacts->count() > 1 ? 's' : '' }} :
                    </h3>
                    @foreach ($cde->societeContacts as $contact)
                        <span class="text-gray-900 dark:text-gray-100">
                            {{ $contact->nom }}
                            <small class="text-gray-700 dark:text-gray-300">
                                {{ $contact->email }}
                            </small>
                        </span>
                    @endforeach
                </div>
            </x-slot>
        </x-tooltip>
    </td>
@endif

<!-- Statut avec couleur dynamique -->
<td class="">
    <div class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
        style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
        {{ $cde->statut->nom }}</div>
</td>

<!-- Lien d'action -->
</tr>
@endforeach
@if ($cdes->count() == 0)
    <tr>
        <td colspan="6" class="text-center py-8">
            Aucune commande en cours
        </td>
    </tr>
@endif
<tr>
    <td colspan="6" class="">
        <a href="{{ route('cde.create') }}" class="btn-select-square rounded-b-md text-center">Créer une
            commande</a>
    </td>
</tr>
</tbody>

</table>
</div>
