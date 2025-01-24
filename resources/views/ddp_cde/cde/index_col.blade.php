<div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200">
    <h1 class="text-3xl font-bold mb-1">
        {{ __('Commandes') }}
    </h1>
    <div class="flex justify-between items-center">
        <p class="text-lg mb-2">
            Commandes en cours
        </p>
        <a href="{{ route('cde.index') }}" class="btn mb-2">Voir tout</a>
    </div>
    <table>
        <thead>
            <tr>
                <th class="px-4 py-2">Numéro</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2"></th>
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Créer par</th>
                <th class="px-4 py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cdes as $cde)
                <tr " class="border-b border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700 cursor-pointer"
                onclick="window.location='{{ route('cde.show', $cde) }}'">
                    <!-- Code -->
                    <td class="min-w-2 text-sm">
                        {{ $cde->code }}
                    </td>

                    <!-- Date de création -->
                    <!-- Date de création -->
                    <td class="pl-2 text-xs leading-5 text-right">
                        <span class="text-nowrap"><span class="pr-1 leading-5">{{ $cde->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="pr-2 text-xs leading-5">
                            <small>{{ $cde->updated_at->format('H:i') }}</small></span>
                    </td>


                    <!-- Nom -->
                    <td>
                        {{ $cde->nom }}
                    </td>

                    <td>
                        {{ $cde->user->first_name }} {{ $cde->user->last_name }}
                    </td>

                    <!-- Statut avec couleur dynamique -->
                    <td class="">
                        <div class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                            style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                            {{ $cde->statut->nom }}</div>
                    </td>

                    <!-- Lien d'action -->
                </tr>
            @endforeach
            <tr>
                <td colspan="6" class="">
                    <a href="{{ route('cde.create') }}" class="btn-select-square rounded-b-md text-center">Créer une commandes</a>
                </td>
            </tr>
        </tbody>

    </table>
</div>
