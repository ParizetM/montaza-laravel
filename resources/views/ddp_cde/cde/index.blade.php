<x-app-layout>
    @section('title', 'Commandes')
    <x-slot name="header">
        <div class="flex items-center gap-20 ">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('ddp_cde.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix et commandes</a>
                    >> Commandes
                </h2>
            </div>
            <form method="GET" action="{!! route('cde.index') !!}"
                class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                <x-select-custom name="statut" id="statut" onchange="this.form.submit()" :selected="request('statut')"
                    class=" mr-2 mb-2 sm:mb-0 ">
                    <x-opt value="">{!! __('Tous les types') !!}</x-opt>
                    @foreach ($cde_statuts as $cde_statut)
                        <x-opt value="{{ $cde_statut->id }}">
                            <div class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                                style="background-color: {{ $cde_statut->couleur }}; color: {{ $cde_statut->couleur_texte }}">
                                {{ $cde_statut->nom }}
                            </div>
                        </x-opt>
                    @endforeach
                </x-select-custom>
                <select name="societe" id="societe" onchange="this.form.submit()"
                    class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                    <option value="" selected>{!! __('Toutes les societes') !!}</option>
                    @foreach ($societes as $societe)
                        <option value="{{ $societe->id }}" {{ request('societe') == $societe->id ? 'selected' : '' }}>
                            {!! $societe->raison_sociale . '&nbsp;&nbsp;' !!}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}"
                    oninput="debounceSubmit(this.form)"
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                <div class="flex items-center ml-4 my-1 ">
                    <label for="nombre" class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                    <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 100)) !!}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2 ">
                </div>
                <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                    {!! __('Rechercher') !!}
                </button>
                <a href="{!! route('cde.create') !!}" class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0">
                    {!! __('Créer une Commande') !!}
                </a>
            </form>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200">

            @if ($cdesGrouped->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr>
                            <x-sortable-header column="code" route="cde.index">Numéro</x-sortable-header>
                            <x-sortable-header column="created_at" route="cde.index">Date</x-sortable-header>
                            <x-sortable-header column="nom" route="cde.index">Nom</x-sortable-header>
                            <x-sortable-header column="user" route="cde.index">Créé par</x-sortable-header>
                            <th class="px-4 py-2" route="cde.index">pour</th>
                            <x-sortable-header column="statut" route="cde.index">Statut</x-sortable-header>
                        </tr>
                    </thead>
                    @include('ddp_cde.cde.partials.index_lignes', [
                        'isSmall' => false,
                        'showCreateButton' => false,
                        'cdesGrouped' => $cdesGrouped,
                    ])
                </table>
            @else
                <!-- Message si aucune commande -->
                <div class="text-center py-8">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucune commande trouvée
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Aucune commande ne correspond à vos critères de
                            recherche.</p>
                        <a href="{{ route('cde.create') }}" class="btn">
                            Créer une nouvelle commande
                        </a>
                    </div>
                </div>
            @endif

            <div class="mt-4 flex justify-center items-center pb-3">
                <div>
                    {{ $cdes->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>
</x-app-layout>
