<x-app-layout>
    @section('title', 'Commandes')
    <x-slot name="header">
        <div class="flex justify-between ">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('ddp_cde.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix et commandes</a>
                    >> Commandes
                </h2>
            </div>
            <form method="GET" action="{!! route('cde.index') !!}"
                class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                <select name="statut" id="statut" onchange="this.form.submit()"
                    class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 ">
                    <option value="" selected>{!! __('Tous les types') !!}</option>
                    @foreach ($cde_statuts as $cde_statut)
                        <option value="{{ $cde_statut->id }}"
                            {{ request('statut') == $cde_statut->id ? 'selected' : '' }}>
                            {!! $cde_statut->nom . '&nbsp;&nbsp;' !!}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}" onblur="this.form.submit()"
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                <div class="flex items-center ml-4 my-1 ">
                    <label for="nombre" class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                    <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 20)) !!}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2 ">
                </div>
                <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                    {!! __('Rechercher') !!}
                </button>
                    <a href="{!! route('cde.create') !!}"
                        class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0">
                        {!! __('Créer une Commande') !!}
                    </a>
            </form>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200">

            <table>
                <thead>
                    <tr>
                        <th class="px-4 py-2">Numéro</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Demandé par</th>
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
                    <td class="pl-2 text-xs leading-5">
                        <span class="text-nowrap">
                            <span class="pr-1 leading-5">{{ $cde->created_at->format('d/m/Y') }}</span>
                            <small>{{ $cde->updated_at->format('H:i') }}</small>
                        </span>
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
                </tbody>

            </table>
            <div class="mt-4 flex justify-center items-center pb-3">
                <div>
                    {{ $cdes->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
