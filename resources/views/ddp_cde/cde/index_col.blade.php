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

                <th class="px-4 py-2">Nom</th>
                @if (!$isSmall)
                    <th class="px-4 py-2">Destinataire</th>
                @endif
                <th class="px-4 py-2">Statut</th>
            </tr>
        </thead>
        @include('ddp_cde.cde.partials.index_lignes', ['isSmall' => $isSmall, 'showCreateButton' => true, 'isMid' => true])
    </table>
</div>
