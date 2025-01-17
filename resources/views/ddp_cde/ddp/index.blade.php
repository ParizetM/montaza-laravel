<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between ">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('ddp_cde.index') }}"
                        class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                    >> Demandes de prix
                </h2>
            </div>
            <form method="GET" action="{!! route('ddp.index') !!}"
                class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                <select name="statut" id="statut" onchange="this.form.submit()"
                    class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 ">
                    <option value="" selected>{!! __('Tous les types') !!}</option>
                    @foreach ($ddp_statuts as $ddp_statut)
                        <option value="{{ $ddp_statut->id }}"
                            {{ request('statut') == $ddp_statut->id ? 'selected' : '' }}>
                            {!! $ddp_statut->nom . '&nbsp;&nbsp;' !!}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}" onblur="this.form.submit()"
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="flex items-center ml-4 my-1 ">
                    <label for="nombre" class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                    <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 20)) !!}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-20 mr-2 ">
                </div>
                <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                    {!! __('Rechercher') !!}
                </button>
                @if (Auth::user()->hasPermission('gerer_les_societes'))
                    <a href="{!! route('ddp.create') !!}"
                        class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0">
                        {!! __('Créer une demande de prix') !!}
                    </a>
                @endif
            </form>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200">
            <h1 class="text-3xl font-bold mb-1">
                {{ __('Demandes de prix') }}
            </h1>
            <div class="flex justify-between items-center">
                <p class="text-lg mb-2">
                    Demandes de prix en cours
                </p>
                <a href="#" class="btn mb-2">Voir tout</a>
            </div>
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
                    @foreach ($ddps as $ddp)
                        <tr " class="border-b border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700 cursor-pointer"
                onclick="window.location='{{ route('ddp.show', $ddp) }}'">
                    <!-- Code -->
                    <td class="min-w-2 text-sm">
                        {{ $ddp->code }}
                    </td>

                    <!-- Date de création -->
                    <!-- Date de création -->
                    <td class="pl-2 text-xs leading-5">
                        <span class="text-nowrap">
                            <span class="pr-1 leading-5">{{ $ddp->created_at->format('d/m/Y') }}</span>
                            <small>{{ $ddp->updated_at->format('H:i') }}</small>
                        </span>
                    </td>


                    <!-- Nom -->
                    <td>
                        {{ $ddp->nom }}
                    </td>
                    <td>
                        {{ $ddp->user->first_name }} {{ $ddp->user->last_name }}
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
                </tbody>

            </table>
            <div class="mt-4 flex justify-center items-center pb-3">
                <div>
                    {{ $ddps->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
