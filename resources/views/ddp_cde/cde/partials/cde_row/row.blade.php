<tr class="border-b border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700 cursor-pointer"
    onclick="window.location='{{ route('cde.show', $cde) }}'">
    @php
        $limit = $isSmall ? 25 : 75;
        $limit = $isMid ? 35 : $limit;
    @endphp
    <!-- Code -->
    <td class="min-w-2 text-sm">
        <div class="flex items-center">
            @include('ddp_cde.cde.partials.cde_row.code_cell', compact('cde', 'isSmall'))
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

    <!-- Nom -->
    <td>

        @if (Str::length($cde->nom) > $limit)
            <x-tooltip position="top">
                <x-slot name="slot_item">{{ Str::limit($cde->nom == $cde->code ? '' : $cde->nom, $limit) }}</x-slot>
                <x-slot name="slot_tooltip">{{ $cde->nom == $cde->code ? '' : $cde->nom }}</x-slot>
            </x-tooltip>
        @else
            {{ $cde->nom == $cde->code ? '' : $cde->nom }}
        @endif
    </td>

    @if (!$isSmall && !$isMid)
        <!-- Créé par -->
        <td>{{ $cde->user->first_name }} {{ $cde->user->last_name }}</td>
    @endif

    @if (!$isSmall)
        <!-- Destinataire -->
        <td>
            <x-tooltip position="left">
                <x-slot name="slot_item">
                    <span class="text-gray-900 dark:text-gray-100">
                        {{ Str::limit($cde->societe->raison_sociale, $limit - 10) }}
                    </span>
                </x-slot>
                <x-slot name="slot_tooltip">
                    <div class="flex flex-col">
                        <h3 class="text-gray-900 dark:text-gray-100 font-bold border-b border-gray-300 dark:border-gray-600 pb-2 mb-2">
                            {{ $cde->societe->raison_sociale }}
                        </h3>
                        <h3 class="text-gray-900 dark:text-gray-100 font-bold">
                            Destinataire{{ $cde->societeContacts->count() > 1 ? 's' : '' }} :
                        </h3>
                        @foreach ($cde->societeContacts as $contact)
                            <span class="text-gray-900 dark:text-gray-100">
                                {{ $contact->nom }}
                                <small class="text-gray-700 dark:text-gray-300">{{ $contact->email }}</small>
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
