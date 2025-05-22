<x-app-layout>
    @section('title', $matiere->designation)

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Matières</a>
                >> {{ $matiere->designation }}
            </h2>
            <a href="{{ route('matieres.edit', $matiere->id) }}" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier
            </a>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6 space-y-6">
        <!-- Carte d'information principale -->
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-gray-100 dark:to-gray-300 bg-clip-text text-transparent">
                        {{ $matiere->designation }}</h1>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-700 rounded-full px-5 py-2 flex items-center gap-2 shadow-inner text-sm font-medium">
                    <span class="text-gray-500 dark:text-gray-400">Référence:</span>
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $matiere->ref_interne }}</span>
                </div>
            </div>

            <!-- Infos principales en grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Sous Famille</p>
                    <p class="font-semibold text-lg">{{ $matiere->sousFamille->nom }}</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Matière</p>
                    <p class="font-semibold text-lg">{{ $matiere->material->nom ?? '-' }}</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Stock actuel</p>
                    <p class="font-semibold text-lg"><x-stock-tooltip matiereId="{{ $matiere->id }}" /></p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Référence unitaire</p>
                    <p class="font-semibold text-lg">{{ $matiere->ref_valeur_unitaire }}</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">DN</p>
                    <p class="font-semibold text-lg">{{ $matiere->dn }}</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Épaisseur</p>
                    <p class="font-semibold text-lg">{{ $matiere->epaisseur ?? '-' }}</p>
                </div>
            </div>

            @if ($matiere->standardVersion != null)
                <div
                    class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-blue-100 dark:border-blue-800 transition-all duration-300 hover:shadow-md">
                    <x-icons.pdf class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Standard</p>
                        <a href="{{ $matiere->standardVersion->chemin_pdf ?? '-' }}"
                            class="font-semibold text-blue-600 dark:text-blue-400 hover:underline hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200"
                            target="_blank">
                            {{ $matiere->standardVersion->standard->nom ?? '-' }} -
                            {{ $matiere->standardVersion->version ?? '-' }}
                        </a>
                    </div>
                </div>
            @else
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg mb-6 border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Standard</p>
                    <p class="font-semibold">Aucun standard</p>
                </div>
            @endif
        </div>

        <!-- Grille avec 2 colonnes pour fournisseurs et mouvements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Carte des fournisseurs -->
            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Fournisseurs</h2>
                </div>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-750">
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Référence</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fournisseur</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Dernier prix</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($fournisseurs as $fournisseur)
                                <tr
                                    @if ($fournisseur->prix != null && $fournisseur->prix->prix_unitaire != null) onclick="window.location.href = '{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}';"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200"
                                    @else
                                    class="" @endif>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $fournisseur->ref_externe ?? 'Aucune référence' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap font-medium">
                                        {{ $fournisseur->raison_sociale }}</td>
                                    @if ($fournisseur->prix != null && $fournisseur->prix->prix_unitaire != null)
                                        <td
                                            class="px-4 py-3 whitespace-nowrap font-semibold text-green-600 dark:text-green-400">
                                            {{ formatNumberArgent($fournisseur->prix->prix_unitaire) . '/' . $matiere->unite->short }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ formatDate(date_string: $fournisseur->prix->date) }}
                                        </td>
                                    @else
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400"
                                            colspan="2">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                Aucun prix
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @if ($fournisseurs->count() == 0)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400"
                                        colspan="4">Aucun fournisseur pour le moment</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
{{--
   ###          ##  #######  ##     ## ######## ######## ########
  ## ##         ## ##     ## ##     ##    ##    ##       ##     ##
 ##   ##        ## ##     ## ##     ##    ##    ##       ##     ##
##     ##       ## ##     ## ##     ##    ##    ######   ########
######### ##    ## ##     ## ##     ##    ##    ##       ##   ##
##     ## ##    ## ##     ## ##     ##    ##    ##       ##    ##
##     ##  ######   #######   #######     ##    ######## ##     ## --}}

            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-100 dark:bg-green-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Ajouter matière</h2>
                </div>
                <form action="{{ route('matieres.ajouter', $matiere->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('POST')

                    <div class="flex flex-col sm:flex-row items-end gap-3">
                        <div class="w-full sm:w-auto">
                            <x-input-label value="Quantité à ajouter" class="text-sm font-medium mb-1" />
                            <div class="relative rounded-md shadow-sm">
                                <x-text-input type="number" name="quantite"
                                    class="w-full pr-12 focus:ring-green-500 focus:border-green-500"
                                    value="{{ old('quantite') }}" placeholder="Quantité" step="0.01"
                                    min="0" required />
                                @if ($matiere->typeAffichageStock() !== 2)
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                        <span
                                            class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                    </div>
                                @endif
                            </div>
                            @error('quantite')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($matiere->typeAffichageStock() !== 1)
                            <p class="mb-2">X</p>
                            <div class="w-full sm:w-auto">
                                <x-input-label value="Valeur unitaire" class="text-sm font-medium mb-1" />
                                <div class="relative rounded-md shadow-sm">
                                    <x-text-input type="number" name="valeur_unitaire"
                                        class="w-full focus:ring-green-500 focus:border-green-500"
                                        value="{{ old('valeur_unitaire', $matiere->ref_valeur_unitaire) }}" placeholder="Valeur unitaire" step="0.01"
                                        min="0" required />
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                        <span
                                            class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                    </div>
                                </div>
                                @error('valeur_unitaire')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2 h-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Ajouter
                        </button>
                    </div>

                    <div class="mt-3">
                        <x-input-label value="Motif de l'ajout" class="text-sm font-medium mb-1" />
                        <x-text-input type="text" name="motif"
                            class="w-full focus:ring-green-500 focus:border-green-500"
                            value="{{ old('motif') }}" placeholder="Indiquez le motif de l'ajout"
                            maxlength="50" required />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 50 caractères</p>
                        @error('motif')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </div>

        </div>

{{--
##     ##  #######  ##     ## ##     ## ######## ##     ## ######## ##    ## ########  ######
###   ### ##     ## ##     ## ##     ## ##       ###   ### ##       ###   ##    ##    ##    ##
#### #### ##     ## ##     ## ##     ## ##       #### #### ##       ####  ##    ##    ##
## ### ## ##     ## ##     ## ##     ## ######   ## ### ## ######   ## ## ##    ##     ######
##     ## ##     ## ##     ##  ##   ##  ##       ##     ## ##       ##  ####    ##          ##
##     ## ##     ## ##     ##   ## ##   ##       ##     ## ##       ##   ###    ##    ##    ##
##     ##  #######   #######     ###    ######## ##     ## ######## ##    ##    ##     ######

 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-emerald-100 dark:bg-emerald-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Mouvements de stock</h2>
                </div>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-750">
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Mouvement</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Utilisateur</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Raison</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($matiere->mouvementStocks && $matiere->mouvementStocks->count() > 0)
                                @foreach ($mouvements->take(5) as $mouvement)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer"
                                        @if ($mouvement->cde_ligne_id != null) onclick="window.open('{{ route('cde.show', $mouvement->cdeLigne->cde->id) }}', '_blank');"
                                            title="Voir la commande {{ $mouvement->cdeLigne->cde->code }}" @endif>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($mouvement->type == 'sortie')
                                                <div class="flex items-center">
                                                    <span
                                                        class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-red-100 text-red-500 dark:bg-red-900 dark:text-red-300 mr-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </span>
                                                    <span class="text-red-500 dark:text-red-400 font-medium">-
                                                        {{ $mouvement->valeur_unitaire ? formatNumber($mouvement->quantite * $mouvement->valeur_unitaire) : $mouvement->quantite }}
                                                        {{ $matiere->unite->short }}</span>
                                                    @if ($mouvement->valeur_unitaire != null)
                                                        <span class="text-gray-500 dark:text-gray-400 ml-1 text-xs">
                                                            ({{ formatNumber($mouvement->quantite) }} × {{ formatNumber($mouvement->valeur_unitaire) }})
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <span
                                                        class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-500 dark:bg-green-900 dark:text-green-300 mr-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7" />
                                                        </svg>
                                                    </span>
                                                    <span class="text-green-500 dark:text-green-400 font-medium">+
                                                        {{ $mouvement->valeur_unitaire ? formatNumber($mouvement->quantite * $mouvement->valeur_unitaire) : $mouvement->quantite }}
                                                        {{ $matiere->unite->short }}</span>
                                                    @if ($mouvement->valeur_unitaire != null)
                                                        <span class="text-gray-500 dark:text-gray-400 ml-1 text-xs">
                                                            ({{ formatNumber($mouvement->quantite) }} × {{ formatNumber($mouvement->valeur_unitaire) }})
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $mouvement->user->first_name . ' ' . $mouvement->user->last_name }}
                                        </td>
                                        <td>
                                            <x-tooltip>
                                                <x-slot name="slot_item">
                                                    <span class="truncate inline-block">
                                                        {{ Str::limit($mouvement->raison, 25, '...') }}
                                                    </span>
                                                </x-slot>
                                                <x-slot name="slot_tooltip">
                                                    {{ $mouvement->raison }}
                                                </x-slot>
                                            </x-tooltip>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ $mouvement->created_at->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400"
                                        colspan="1000">Aucun mouvement</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

{{--
########  ######## ######## #### ########  ######## ########
##     ## ##          ##     ##  ##     ## ##       ##     ##
##     ## ##          ##     ##  ##     ## ##       ##     ##
########  ######      ##     ##  ########  ######   ########
##   ##   ##          ##     ##  ##   ##   ##       ##   ##
##    ##  ##          ##     ##  ##    ##  ##       ##    ##
##     ## ########    ##    #### ##     ## ######## ##     ##  --}}

            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-red-100 dark:bg-red-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Retirer matière</h2>
                </div>

                <!-- Sélecteur de mode -->
                <div class="mb-6">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-1 grid grid-cols-2 gap-1">
                        <button type="button" id="mode-standard-btn"
                            class="mode-selector flex-1 py-2 rounded-md text-center font-medium mode-active w-full">
                            Mode standard
                        </button>
                        @if ($matiere->typeAffichageStock() == 2)
                        <button type="button" id="mode-adjustment-btn"
                            class="mode-selector flex-1 py-2 rounded-md text-center font-medium">
                            Mode ajustement
                        </button>
                        @else
                        <x-tooltip  position="top" class="w-full" >
                            <x-slot name="slot_item"  >
                                <div class="w-full">
                                <button type="button" id="mode-adjustment-btn" disabled
                            class="mode-selector flex-1 py-2 rounded-md text-center font-medium w-full">
                            Mode ajustement
                        </button>
                        </div>
                            </x-slot>
                            <x-slot name="slot_tooltip" >
                                Ce mode n'est pas utilisable pour les matières de ce type de stockage
                            </x-slot>
                        </x-tooltip>
                        @endif
                    </div>
                </div>

                <!-- Mode standard (formulaire existant) -->
                <div id="mode-standard-form">
                    <form action="{{ route('matieres.retirer', $matiere->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('POST')

                        <div class="flex flex-col sm:flex-row items-end gap-3">
                            <div class="w-full sm:w-auto">
                                <x-input-label value="Quantité à retirer" class="text-sm font-medium mb-1" />
                                <div class="relative rounded-md shadow-sm">
                                    <x-text-input type="number" name="quantite"
                                        class="w-full pr-12 focus:ring-red-500 focus:border-red-500"
                                        value="{{ old('quantite') }}" placeholder="Quantité" step="0.01"
                                        min="0" required />
                                    @if ($matiere->typeAffichageStock() !== 2)
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                            <span
                                                class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                        </div>
                                    @endif
                                </div>
                                @error('quantite')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ($matiere->typeAffichageStock() !== 1)
                                <p class="mb-2">X</p>
                                <div class="w-full sm:w-auto">
                                    <x-input-label value="Valeur unitaire" class="text-sm font-medium mb-1" />
                                    <select name="valeur_unitaire"
                                        class="rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-red-500 focus:border-red-500 block w-full">
                                        @foreach ($matiere->stock->where("quantite",'!=',0) as $stock)
                                            @if ($stock->valeur_unitaire > 0)
                                                <option value="{{ $stock->valeur_unitaire }}">
                                                    {{ formatNumber($stock->valeur_unitaire) }}
                                                    {{ $matiere->unite->short }} ({{ formatNumber($stock->quantite) }} disponible)</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('valeur_unitaire')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2 h-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                                Retirer
                            </button>
                        </div>

                        <div class="mt-3">
                            <x-input-label value="Motif du retrait" class="text-sm font-medium mb-1" />
                            <x-text-input type="text" name="motif"
                                class="w-full focus:ring-red-500 focus:border-red-500"
                                value="{{ old('motif') }}" placeholder="Indiquez le motif du retrait"
                                maxlength="50" required />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 50 caractères</p>
                            @error('motif')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>
                </div>

                <!-- Mode ajustement (nouveau formulaire) -->
                <div id="mode-adjustment-form" class="hidden">
                    <form action="{{ route('matieres.ajuster', $matiere->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <x-input-label value="Entrée de stock à ajuster" class="text-sm font-medium mb-1" />
                            <select name="stock_id" id="stock-select" class="rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-red-500 focus:border-red-500 block w-full mb-2">
                                <option value="">Sélectionnez une entrée de stock</option>
                                @foreach ($matiere->stock->where("quantite",'!=',0) as $stock)
                                    @if ($stock->valeur_unitaire > 0)
                                        <option value="{{ $stock->id }}" data-value="{{ $stock->valeur_unitaire }}" data-qty="{{ $stock->quantite }}">
                                            {{ formatNumber($stock->valeur_unitaire) }} {{ $matiere->unite->short }}
                                            ({{ formatNumber($stock->quantite) }} disponible)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="stock-info" class="text-sm text-gray-500 dark:text-gray-400 hidden">
                                <p>Valeur unitaire actuelle: <span id="current-value" class="font-medium"></span></p>
                                <p>Quantité disponible: <span id="available-qty" class="font-medium"></span></p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-input-label value="Quantité à ajuster" class="text-sm font-medium mb-1" />
                            <div class="relative rounded-md shadow-sm">
                                <x-text-input type="number" name="quantite_ajuster" id="qty-to-adjust"
                                    class="w-full pr-12 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Quantité à ajuster" step="0.01"
                                    min="0" required />
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Quantité du stock à ajuster (doit être inférieure ou égale à la quantité disponible)
                            </p>
                        </div>

                        <div class="mb-3">
                            <x-input-label value="Nouvelle valeur unitaire" class="text-sm font-medium mb-1" />
                            <div class="relative rounded-md shadow-sm">
                                <x-text-input type="number" name="nouvelle_valeur" id="new-value-input"
                                    class="w-full pr-12 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Nouvelle valeur unitaire" step="0.01"
                                    min="0" required />
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                La nouvelle valeur doit être inférieure à la valeur actuelle
                            </p>
                        </div>

                        <div class="mb-3">
                            <x-input-label value="Motif de l'ajustement" class="text-sm font-medium mb-1" />
                            <x-text-input type="text" name="motif"
                                class="w-full focus:ring-red-500 focus:border-red-500"
                                placeholder="Indiquez le motif de l'ajustement" maxlength="50" required />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Maximum 50 caractères
                            </p>
                        </div>

                        <div>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                                Ajuster la valeur unitaire
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Éléments de l'interface
                    const modeStandardBtn = document.getElementById('mode-standard-btn');
                    const modeAdjustmentBtn = document.getElementById('mode-adjustment-btn');
                    const modeStandardForm = document.getElementById('mode-standard-form');
                    const modeAdjustmentForm = document.getElementById('mode-adjustment-form');

                    // Gestion du mode standard
                    modeStandardBtn.addEventListener('click', function() {
                        modeStandardBtn.classList.add('mode-active');
                        modeAdjustmentBtn.classList.remove('mode-active');
                        modeStandardForm.classList.remove('hidden');
                        modeAdjustmentForm.classList.add('hidden');
                    });

                    // Gestion du mode ajustement
                    modeAdjustmentBtn.addEventListener('click', function() {
                        modeAdjustmentBtn.classList.add('mode-active');
                        modeStandardBtn.classList.remove('mode-active');
                        modeAdjustmentForm.classList.remove('hidden');
                        modeStandardForm.classList.add('hidden');
                    });

                    // Gestion du sélecteur de stock pour le mode ajustement
                    const stockSelect = document.getElementById('stock-select');
                    const stockInfo = document.getElementById('stock-info');
                    const currentValue = document.getElementById('current-value');
                    const availableQty = document.getElementById('available-qty');
                    const newValueInput = document.getElementById('new-value-input');
                    const qtyToAdjust = document.getElementById('qty-to-adjust');

                    stockSelect.addEventListener('change', function() {
                        if (this.value) {
                            const selectedOption = this.options[this.selectedIndex];
                            const value = selectedOption.dataset.value;
                            const qty = selectedOption.dataset.qty;

                            currentValue.textContent = value + ' {{ $matiere->unite->short }}';
                            availableQty.textContent = qty;
                            stockInfo.classList.remove('hidden');

                            // Pré-remplir avec les valeurs pour faciliter l'ajustement
                            newValueInput.value = value;
                            newValueInput.max = value;
                            qtyToAdjust.value = qty; // Pré-remplir avec la quantité disponible
                            qtyToAdjust.max = qty;   // Limiter la quantité à ajuster
                        } else {
                            stockInfo.classList.add('hidden');
                            newValueInput.value = '';
                            qtyToAdjust.value = '';
                        }
                    });

                    // Validation supplémentaire pour la quantité à ajuster
                    qtyToAdjust.addEventListener('input', function() {
                        const max = parseFloat(this.max);
                        const value = parseFloat(this.value);

                        if (value > max) {
                            this.value = max;
                        }
                    });
                });
            </script>

            <style>
                .mode-selector {
                    transition: all 0.2s ease-in-out;
                }
                .mode-active {
                    background-color: rgba(239, 68, 68, 0.2);
                    color: rgb(239, 68, 68);
                    font-weight: 600;
                }
                .dark .mode-active {
                    background-color: rgba(239, 68, 68, 0.3);
                    color: rgb(248, 113, 113);
                }
            </style>
        </div>
    </div>
    <!-- Graphique d'évolution du stock -->
        @if ($dates == null)
            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-yellow-100 dark:bg-yellow-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-yellow-600 dark:text-yellow-400 font-medium">Aucun mouvement pour cette matière</p>
                </div>
            </div>
        @else
            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Évolution du stock</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div
                        class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                        <x-input-label for="startDate" class="block mb-2">Date de début :</x-input-label>
                        <select id="startDate" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($dates as $date)
                                <option value="{{ $date }}">{{ $date }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                        <x-input-label for="endDate" class="block mb-2">Date de fin :</x-input-label>
                        <select id="endDate" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($dates->reverse() as $date)
                                <option value="{{ $date }}">{{ $date }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="mb-6 chart-container" style="position: relative; height:300px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('myChart').getContext('2d');

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates),
                    datasets: [{
                        label: 'Quantité sur le temps',
                        data: @json($quantites),
                        borderColor: '#4F46E5', // Indigo-600
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true,
                        pointBackgroundColor: '#4F46E5',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'hour',
                                displayFormats: {
                                    hour: 'yyyy-MM-dd HH:mm',
                                },
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            type: 'linear',
                            grid: {
                                borderDash: [2]
                            }
                        }
                    }
                }
            });

            // Gestionnaires des menus déroulants
            const startDateSelect = document.getElementById('startDate');
            const endDateSelect = document.getElementById('endDate');

            // Fonction pour mettre à jour les limites de l'axe X
            const updateChartLimits = () => {
                const startDate = startDateSelect.value;
                const endDate = endDateSelect.value;

                if (new Date(startDate) <= new Date(endDate)) {
                    myChart.options.scales.x.min = startDate;
                    myChart.options.scales.x.max = endDate;
                    myChart.update();
                } else {
                    alert("La date de début doit être inférieure ou égale à la date de fin.");
                }
            };

            // Ajoute des événements de changement aux sélecteurs
            startDateSelect.addEventListener('change', updateChartLimits);
            endDateSelect.addEventListener('change', updateChartLimits);
        });
    </script>
    @endif
</x-app-layout>
