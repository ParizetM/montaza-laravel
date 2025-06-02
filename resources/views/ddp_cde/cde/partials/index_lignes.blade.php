@php
    $isMid = $isMid ?? false;
    $isSmall = $isSmall ?? false;
    $showCreateButton = $showCreateButton ?? false;
@endphp

<tbody>
    @foreach ($cdes as $cde)
        <tr class="border-b border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700 cursor-pointer"
            onclick="window.location='{{ route('cde.show', $cde) }}'">

            <!-- Code -->
            <td class="min-w-2 text-sm">
                <div class="flex items-center">

                    @php
                        $total = $cde->cdeLignes->where('conditionnement', '!=', 0)->count();
                    @endphp
                    @if ($total > 0)
                        <x-tooltip position="top">
                            <x-slot name="slot_item">
                                <x-icon :size="1" type="error_icon" class="fill-red-500 dark:fill-red-400" />
                            </x-slot>
                            <x-slot name="slot_tooltip">
                                <span class="text-red-600 dark:text-red-400 font-semibold">
                                    Les stocks à prendre en compte pour cette commande n'ont pas encore été précisés.
                                </span>
                            </x-slot>
                        </x-tooltip>
                    @endif
                    <x-tooltip position="right">
                        <x-slot name="slot_item">

                            <span class="{{ $isSmall ? 'whitespace-nowrap' : '' }}">{{ $cde->code }}</span>
                            {{-- <x-icons.cde size="1.2"
                                class="ml-1 fill-gray-700 dark:fill-gray-300 border-b-2 border-gray-700 dark:border-gray-300" /> --}}
                        </x-slot>
                        <x-slot name="slot_tooltip">
                            <div class="flex flex-col max-w-md">
                                <h3 class="text-gray-900 dark:text-gray-100 font-bold mb-2">
                                    Contenu de la commande
                                </h3>
                                @if ($cde->ddp_cde_statut_id == 4)
                                    <div
                                        class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6 rounded shadow-md">
                                        <div class="flex items-center">
                                            <x-icon type="error_icon" size="2" class="text-red-500 mr-3" />
                                            <div>
                                                <p class="font-bold text-lg">Cette commande a été annulée</p>
                                                <p>Date d'annulation:
                                                    {{ $cde->updated_at ? Carbon\Carbon::parse($cde->updated_at)->format('d/m/Y H:i') : 'Non spécifiée' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                                                    class="border-b dark:border-gray-600 {{ $cde->ddp_cde_statut_id == 4 || $ligne->ddp_cde_statut_id == 4 || $ligne->date_livraison_reelle == null ? 'line-through' : '' }}">
                                                    <td class="px-2 py-1 text-xs">{{ $ligne->poste }}</td>
                                                    <td class="px-2 py-1 text-xs">{{ $ligne->designation }}
                                                        @if ($ligne->conditionnement != 0)
                                                            <br />
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                <x-icons.turn-left
                                                                    class="inline-block mr-2 -rotate-180 fill-gray-700 dark:fill-gray-400"
                                                                    size="1.5" />
                                                                Par conditionnement de
                                                                {{ formatNumber($ligne->conditionnement) }}
                                                                {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-2 py-1 text-xs text-right whitespace-nowrap">
                                                        {{ formatNumber($ligne->quantite) }}
                                                        {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}
                                                    </td>
                                                    <td class="px-2 py-1 text-xs text-right whitespace-nowrap">
                                                        {{ formatNumberArgent($ligne->prix) }}
                                                    </td>
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
                </div>
            </td>

            <!-- Date de création -->
            <td class="pl-2 text-xs leading-5 {{ $isSmall ? 'text-center' : '' }}">
                <span class="text-nowrap">
                    <span class="pr-1 leading-5">{{ $cde->created_at->format('d/m/Y') }}</span>
                    @if (!$isSmall)
                        <small>{{ $cde->updated_at->format('H:i') }}</small>
                    @endif
                </span>
            </td>

            @if (!$isSmall)
                <td class="pr-2 text-xs leading-5">
                    <small>{{ $cde->updated_at->format('H:i') }}</small>
                </td>
            @endif

            <!-- Nom -->
            <td>
                @if (Str::length($cde->nom) > 25)
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

            @if (!$isSmall && !$isMid)
                <!-- Créé par -->
                <td>
                    {{ $cde->user->first_name }} {{ $cde->user->last_name }}
                </td>
            @endif

            @if (!$isSmall)
                <!-- Destinataire -->
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

            <!-- Statut -->
            <td class="">
                <div class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                    style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                    {{ $cde->statut->nom }}
                </div>
            </td>
        </tr>
    @endforeach

    @if ($cdes->count() == 0)
        <tr>
            <td colspan="{{ $isSmall ? '4' : '6' }}" class="text-center py-8">
                {{ $isSmall ? 'Aucune commande en cours' : 'Aucune commande trouvée' }}
            </td>
        </tr>
    @endif

    @if ($showCreateButton)
        <tr>
            <td colspan="{{ $isSmall ? '4' : '6' }}" class="">
                <a href="{{ route('cde.create') }}" class="btn-select-square rounded-b-md text-center">
                    Créer une commande
                </a>
            </td>
        </tr>
    @endif
</tbody>
