<div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200">
    <div class="flex justify-between items-center">

    <h1 class="text-3xl font-bold mb-1">
        <a href="{{ route('ddp.index') }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
            {{ __('Demandes de prix') }}
        </a>
    </h1>
    <a href="{{ route('ddp.index') }}" class="btn mb-2">Voir tout</a>

</div>

        <p class="text-lg mb-2">
            Demandes de prix en cours
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
                @if(!$isSmall)
                <th class="px-4 py-2">Créer par</th>
                @endif
                <th class="px-4 py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ddps as $ddp)
                <tr " class="border-b border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700 cursor-pointer"
                onclick="window.location='{{ route('ddp.show', $ddp) }}'">
                    <!-- Code -->
                    <td class="min-w-2 text-sm">
                        {{ $ddp->code }}
                    </td>

                    <!-- Date de création -->
                    <!-- Date de création -->
                    <td class="pl-2 text-xs leading-5 {{ $isSmall ? 'text-center' : 'text-right' }}">
                        <span class="text-nowrap"><span class="pr-1 leading-5">{{ $ddp->created_at->format('d/m/Y') }}</span>
                    </td>
                    @if(!$isSmall)
                    <td class="pr-2 text-xs leading-5">
                        <small>{{ $ddp->updated_at->format('H:i') }}</small></span>
                    </td>
                    @endif

                    <!-- Nom -->
                    <td>
                        {{ $ddp->nom }}
                    </td>
                    @if(!$isSmall)
                    <td>
                        {{ $ddp->user->first_name }} {{ $ddp->user->last_name }}
                    </td>
                    @endif
                    <!-- Statut avec couleur dynamique -->
                    <td class="">
                        <div class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                            style="background-color: {{ $ddp->statut->couleur }}; color: {{ $ddp->statut->couleur_texte }}">
                            {{ $ddp->statut->nom }}</div>
                    </td>

                    <!-- Lien d'action -->
                </tr>
            @endforeach
            @if ($ddps->count() == 0)
                <tr>
                    <td colspan="6" class="text-center py-8">
                        Aucune demande de prix en cours
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="6" class="">
                    <a href="{{ route('ddp.create') }}" class="btn-select-square rounded-b-md text-center">Créer une demande de prix</a>
                </td>
            </tr>
        </tbody>

    </table>
</div>
