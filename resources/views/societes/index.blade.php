<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Sociétés') !!}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center ">

                <form method="GET" action="{!! route('societes.index') !!}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                    <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                        <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 20)) !!}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="ml-2 btn sm:mt-0 md:mt-0 lg:mt-0">
                        {!! __('Rechercher') !!}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600 dark:text-gray-300">
                                        {!! __('Nom') !!}
                                    </th>
                                    <th
                                        class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600 dark:text-gray-300">
                                        {!! __('Type') !!}
                                    </th>
                                    <th
                                        class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600 dark:text-gray-300">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-100">
                                @foreach ($societes as $societe)
                                    <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-900 border-b border-gray-200 dark:border-gray-700"
                                        onclick="document.getElementById('details-{{ $societe->id }}').classList.toggle('hidden')">
                                        <td class="text-left py-3 px-4">
                                            {!! $societe->raison_sociale !!}
                                        </td>
                                        <td class="text-left py-3 px-4">
                                            {!! $societe->societeType->nom !!}
                                        </td>
                                        <td class="block py-3 px-4">
                                            <x-icon :size="1" type="arrow_back"
                                                class="float-right -rotate-90 mr-5" />
                                        </td>
                                    </tr>
                                    <tr id="details-{{ $societe->id }}" class="hidden">
                                        <td colspan="3"
                                            class="bg-gray-100 dark:bg-gray-900 rounded-r-md rounded-l-md rounded-t-none">
                                            <div class="grid grid-cols-2">
                                                <div class="p-4">
                                                    <x-Copiable_text titre="Siren : " text="{{ $societe->siren }}" />
                                                    <x-Copiable_text titre="Forme juridique : "
                                                        text="{{ $societe->formeJuridique->code }}" />
                                                    <x-Copiable_text titre="Code APE : "
                                                        text="{{ $societe->codeApe->code }}" />
                                                    <x-Copiable_text titre="N° de TVA intra. : "
                                                        text="{{ $societe->numero_tva }}" />
                                                    <!-- Add more details as needed -->
                                                </div>
                                                <div class="">
                                                    <table class="min-w-full">
                                                        <tbody>
                                                            @foreach ($societe->etablissements as $etablissement)
                                                                <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800"
                                                                    onclick="document.getElementById('details-{{ $societe->id }}-{{ $etablissement->id }}').classList.toggle('hidden')">
                                                                    <td class="text-left py-3 px-4">
                                                                        {!! $etablissement->nom !!}
                                                                    </td>
                                                                    <td class="block py-3 px-4">
                                                                        <x-icon :size="1" type="arrow_back"
                                                                            class="float-right -rotate-90 mr-5" />
                                                                    </td>
                                                                </tr>
                                                                <tr id="details-{{ $societe->id }}-{{ $etablissement->id }}"
                                                                    class="hidden">
                                                                    <td colspan="3"
                                                                        class="bg-gray-200 dark:bg-gray-900 rounded-r-md rounded-l-md rounded-t-none">
                                                                        <div class="">
                                                                            <div class="float-left p-4">
                                                                                <x-Copiable_text titre="Adresse : "
                                                                                    text="{{ $etablissement->adresse }}" />
                                                                                <x-Copiable_text titre="Code postal : "
                                                                                    text="{{ $etablissement->code_postal }}" />
                                                                                <x-Copiable_text titre="Ville : "
                                                                                    text="{{ $etablissement->ville }}" />
                                                                                <x-Copiable_text titre="Région : "
                                                                                    text="{{ $etablissement->region }}" />
                                                                                <x-Copiable_text titre="Pays : "
                                                                                    text="{{ $etablissement->pays->nom }}" />
                                                                                <x-Copiable_text titre="Siret : "
                                                                                    text="{{ $etablissement->siret }}" />
                                                                            </div>
                                                                            <div class="float-right p-4">
                                                                                    <button type="button" class="btn mb-4 dark:bg-gray-800"
                                                                                        title="Contacts"
                                                                                        x-data=""
                                                                                        x-on:click.prevent="$dispatch('open-modal', 'contacts-modal-{{ $etablissement->id }}')">
                                                                                        <x-icon :size="1.5" type="contact" class="icons-no_hover " />
                                                                                    </button>
                                                                                    @php
                                                                                        $contacts = $etablissement->societeContacts;
                                                                                    @endphp
                                                                                    <x-modals.contacts name="contacts-modal-{{ $etablissement->id }}" :contacts="$contacts" />
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </td>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>


</x-app-layout>
