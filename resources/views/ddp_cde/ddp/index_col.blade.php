<div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200">
    <h1 class="text-3xl font-bold mb-1">
        {{ __('Demandes de prix') }}
    </h1>
    <div class="flex justify-between items-center">
        <p class="text-lg mb-2">
            Demandes de prix en cours
        </p>
        <a href="{{ route('ddp.index') }}" class="btn mb-2">Voir tout</a>
    </div>
    <table>
        <thead>
            <tr>
                <th class="px-4 py-2">Numéro</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2"></th>
                <th class="px-4 py-2">Nom</th>
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
                    <td class="pl-2 text-xs leading-5 text-right">
                        <span class="text-nowrap"><span class="pr-1 leading-5">{{ $ddp->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="pr-2 text-xs leading-5">
                            <small>{{ $ddp->updated_at->format('H:i') }}</small></span>
                    </td>


                    <!-- Nom -->
                    <td>
                        {{ $ddp->nom }}
                    </td>

                    <!-- Statut avec couleur dynamique -->
                    <td class="">
                        <div class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                            style="background-color: {{ $ddp->statut->couleur }}; color: {{ $ddp->statut->couleur_texte }}">
                            {{ $ddp->statut->nom }}</div>
                    </td>

                    <!-- Lien d'action -->
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="">
                    <a href="{{ route('ddp.create') }}" class="btn-select-square rounded-b-md text-center">Créer une demande de prix</a>
                </td>
            </tr>
        </tbody>

    </table>
</div>
