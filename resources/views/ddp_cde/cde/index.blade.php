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
                <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}"
                    onblur="this.form.submit()"
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                <div class="flex items-center ml-4 my-1 ">
                    <label for="nombre" class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                    <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 20)) !!}"
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

            <table>
                <thead>
                    <tr>
                        <th class="px-4 py-2">Numéro</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Créé par</th>
                        <th class="px-4 py-2">pour</th>
                        <th class="px-4 py-2">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cdes as $cde)
                        <tr " class="border-b border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700 cursor-pointer"
                onclick="window.location='{{ route('cde.show', $cde) }}'">
                    <!-- Code -->
                    <td class="min-w-2 text-sm">
                        <x-tooltip position="right">
                            <x-slot name="slot_item">
                                <div class="flex items-center">
                                    {{ $cde->code }}
                                    <x-icons.cde size="1.2" class="ml-1 fill-gray-700 dark:fill-gray-300 border-b-2 border-gray-700 dark:border-gray-300" />
                                </div>
                            </x-slot>
                            <x-slot name="slot_tooltip">
                                <div class="flex flex-col max-w-md">
                                    <h3 class="text-gray-900 dark:text-gray-100 font-bold mb-2">
                                        Contenu de la commande
                                    </h3>
                                    @if($cde->cdeLignes->count() > 0)
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
                                                @foreach($cde->cdeLignes as $ligne)
                                                <tr class="border-b dark:border-gray-600">
                                                    <td class="px-2 py-1 text-xs">{{ $ligne->poste }}</td>
                                                    <td class="px-2 py-1 text-xs">{{ $ligne->designation }}</td>
                                                    <td class="px-2 py-1 text-xs text-right">{{ formatNumber($ligne->quantite) }} {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}</td>
                                                    <td class="px-2 py-1 text-xs text-right">{{ formatNumberArgent($ligne->prix) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="font-bold bg-gray-50 dark:bg-gray-800">
                                                    <td colspan="3" class="px-2 py-1 text-xs text-right">Total:</td>
                                                    <td class="px-2 py-1 text-xs text-right">
                                                        {{ formatNumberArgent($cde->total_ht) }}
                                                         </td>
                                                </tr>
                                                <tr class="font-bold bg-gray-50 dark:bg-gray-800">
                                                    <td colspan="3" class="px-2 py-1 text-xs text-right">Total TTC:</td>
                                                    <td class="px-2 py-1 text-xs text-right">
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
                    </td>

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
                    <td>
                        <x-tooltip  position="top" >
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
