@php
    $isMid = $isMid ?? false;
    $isSmall = $isSmall ?? false;
    $showCreateButton = $showCreateButton ?? false;
    $cdesGrouped = $cdesGrouped ?? null;
@endphp

<tbody>
    @if (!$isSmall && !$isMid && isset($cdesGrouped) && $cdesGrouped->count() > 0)
        @foreach ($cdesGrouped as $entiteNom => $cdesParEntite)
            <!-- Ligne de séparation avec le nom de l'entité -->
            <tr>
                <td colspan="6" class="py-4">
                    <div class="flex items-center justify-between border-b-2 border-gray-300 dark:border-gray-600 pb-2 mb-2">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-800">
                                <x-icons.group class="w-4 h-4 text-gray-600 dark:text-gray-300" />
                            </span>
                            <span class="text-base font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                {{ $entiteNom }}
                            </span>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 px-3 py-1 rounded-full shadow-sm">
                            {{ $cdesParEntite->count() }} commande{{ $cdesParEntite->count() > 1 ? 's' : '' }}
                        </span>
                    </div>
                </td>
            </tr>

            @foreach ($cdesParEntite as $cde)
                @include('ddp_cde.cde.partials.cde_row.row', compact('cde', 'isSmall', 'isMid'))
            @endforeach
        @endforeach
    @else
        @foreach ($cdes as $cde)
            @include('ddp_cde.cde.partials.cde_row.row', compact('cde', 'isSmall', 'isMid'))
        @endforeach
    @endif

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
