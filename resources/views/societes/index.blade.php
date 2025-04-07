<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Sociétés') !!}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center ">

                <form method="GET" action="{!! route('societes.index') !!}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                    <select name="type" id="type" onchange="this.form.submit()"
                        class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 ">
                        <option value="" selected>{!! __('Tous les types') !!}</option>
                        @foreach ($societeTypes as $societeType)
                            <option value="{{ $societeType->id }}"
                                {{ request('type') == $societeType->id ? 'selected' : '' }}>
                                {!! $societeType->nom . '&nbsp;&nbsp;' !!}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}"
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                        <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 20)) !!}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2 ">
                    </div>
                    <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                        {!! __('Rechercher') !!}
                    </button>
                    @if (Auth::user()->hasPermission('gerer_les_societes'))
                        <a href="{!! route('societes.create') !!}"
                            class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0">
                            {!! __('Ajouter une société') !!}
                        </a>
                    @endif
                </form>

            </div>
        </div>
    </x-slot>

    <div class="py-8 ">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg shadow-md">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead
                                class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800">
                                <tr>
                                    <th class="w-1">
                                    </th>
                                    <th
                                        class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600 dark:text-gray-300">
                                        {!! __('Raison sociale') !!}
                                    </th>
                                    <th
                                        class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600 dark:text-gray-300">
                                        {!! __('Type') !!}
                                    </th>

                                    <th
                                        class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600 dark:text-gray-300 w-2">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-100">
                                @foreach ($societes as $societe)
                                    <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-900 border-b border-gray-200 dark:border-gray-700"
                                        onclick="document.getElementById('details-{{ $societe->id }}').classList.toggle('hidden');
                                        rotateArrow({{ $societe->id }})">
                                        <td class="block py-3 px-4 max-w-fit">
                                            <x-icon :size="1" type="arrow_back" id="arrow-{{ $societe->id }}"
                                                class="-rotate-180 mr-5 mt-1" />
                                        </td>
                                        <td class="text-left py-3 px-4">
                                            {!! $societe->raison_sociale !!}
                                        </td>
                                        <td class="text-left py-3 px-4">
                                            {!! $societe->societeType->nom !!}
                                        </td>

                                        <td class="">
                                            <a href="{{ route('societes.show', $societe->id) }}"
                                                class="btn float-right  mr-1">
                                                <x-icon :size="1" type="open_in_new" />
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="details-{{ $societe->id }}" class="hidden transition-all ease-in-out duration-300">
                                        <td colspan="4"
                                            class="bg-gray-100 dark:bg-gray-900 rounded-r-md rounded-l-md rounded-t-none p-0">
                                            <div class="grid grid-cols-2">
                                                <div class="mb-4">
                                                    <table class="min-w-full">
                                                        <tbody>
                                                            @foreach ($societe->etablissements as $etablissement)
                                                                <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 transition ease-in-out duration-300"
                                                                    onclick="document.getElementById('details-{{ $societe->id }}-{{ $etablissement->id }}').classList.toggle('hidden');
                                                                    rotateArrow('{{ $societe->id }}-{{ $etablissement->id }}')">
                                                                    <td class="block py-3 pl-4 pr-2 w-1">
                                                                        <x-icon :size="1" type="arrow_back"
                                                                            id="arrow-{{ $societe->id }}-{{ $etablissement->id }}"
                                                                            class="ml-6 -rotate-180 mt-1" />
                                                                    </td>
                                                                    <td class="text-left py-3 px-4">
                                                                        {!! $etablissement->nom !!}
                                                                    </td>
                                                                </tr>
                                                                <tr id="details-{{ $societe->id }}-{{ $etablissement->id }}"
                                                                    class="hidden">
                                                                    <td colspan="3"
                                                                        class="bg-gray-200 dark:bg-gray-850 ">
                                                                        <div class="flex justify-between">
                                                                            <div class="float-left p-4 ">
                                                                                <x-copiable_text titre="Adresse : "
                                                                                    text="{{ $etablissement->adresse }}" />
                                                                                <x-copiable_text titre="Code postal : "
                                                                                    text="{{ $etablissement->code_postal }}" />
                                                                                <x-copiable_text titre="Ville : "
                                                                                    text="{{ $etablissement->ville }}" />
                                                                                <x-copiable_text titre="Région : "
                                                                                    text="{{ $etablissement->region }}" />
                                                                                <x-copiable_text titre="Pays : "
                                                                                    text="{{ $etablissement->pays->nom }}" />
                                                                                <x-copiable_text titre="Siret : "
                                                                                    text="{{ $etablissement->siret }}" />

                                                                            </div>
                                                                            <div class="float-right p-4">
                                                                                <button type="button"
                                                                                    class="btn mb-4 dark:bg-gray-800"
                                                                                    title="Contacts"
                                                                                    x-data=""
                                                                                    x-on:click.prevent="$dispatch('open-modal', 'contacts-modal-{{ $etablissement->id }}')">
                                                                                    <x-icon :size="1.5"
                                                                                        type="contact"
                                                                                        class="icons-no_hover " />
                                                                                </button>
                                                                                @php
                                                                                    $contacts =
                                                                                        $etablissement->societeContacts;
                                                                                @endphp
                                                                                <x-modals.contacts
                                                                                    name="contacts-modal-{{ $etablissement->id }}"
                                                                                    :contacts="$contacts" />
                                                                            </div>
                                                                        </div>
                                                                        <div class=" p-4 pt-0">
                                                                            <div></div>
                                                                            <label for="commentaire"
                                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{!! __('Commentaire') !!}</label>
                                                                            <textarea rows="3" id="commentaire" name="commentaire"
                                                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100"
                                                                                data-etablissement-id="{{ $etablissement->id }}" onblur="updateCommentaireEtablissement(this)">{{ $etablissement->commentaire ? $etablissement->commentaire->contenu : '' }}</textarea>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr class="cursor-pointer">
                                                                <td class="text-left hover:bg-gray-100 dark:hover:bg-gray-700 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-br-xl"
                                                                    colspan="2">
                                                                    <a href="{{ route('etablissements.create', $societe->id) }}"
                                                                        class="w-full h-full flex items-center px-4">
                                                                        <span class=" text-4xl">+
                                                                            &nbsp;</span>{!! __('Ajouter un établissement') !!}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="p-4">
                                                    <x-copiable_text titre="Siren : " text="{{ $societe->siren }}" />
                                                    <x-copiable_text titre="Forme juridique : "
                                                        text="{{ $societe->formeJuridique->code }}" />
                                                    <x-copiable_text titre="Code APE : "
                                                        text="{{ $societe->codeApe->code }}" />
                                                    <x-copiable_text titre="N° de TVA intra. : "
                                                        text="{{ $societe->numero_tva }}" />
                                                    <x-copiable_text titre="Téléphone : "
                                                        text="{{ $societe->telephone }}" />
                                                    <x-copiable_text titre="Email : " text="{{ $societe->email }}" />
                                                    <div class="">
                                                        <span class="font-semibold">{!! __('Site web : ') !!}</span>
                                                        <a href="https://{{ $societe->site_web }}" target="_blank"
                                                            class="text-blue-500 hover:underline">
                                                            {{ parse_url($societe->site_web, PHP_URL_HOST) ?: $societe->site_web }}
                                                        </a>
                                                    </div>
                                                    <div class="mt-4">
                                                        <label for="commentaire"
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{!! __('Commentaire') !!}</label>
                                                        <textarea rows="3" id="commentaire" name="commentaire"
                                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100"
                                                            data-societe-id="{{ $societe->id }}" onblur="updateCommentaireSociete(this)">{{ $societe->commentaire ? $societe->commentaire->contenu : '' }}</textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($societes->isEmpty())
                                    <tr>
                                        <td colspan="100" class="text-center py-4">
                                            {!! __('Aucune société trouvée') !!}
                                        </td>
                                    </tr>

                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 flex justify-center items-center pb-3">
                    <div>
                        {{ $societes->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function rotateArrow(id) {
            console.log(id);
            const arrow = document.getElementById('arrow-' + id);
            if (arrow.classList.contains('-rotate-180')) {
                arrow.classList.remove('-rotate-180');
                arrow.classList.add('-rotate-90');
                arrow.classList.remove('-mt-2');
                arrow.classList.add('-mb-2');
            } else {
                arrow.classList.add('-rotate-180');
                arrow.classList.remove('-rotate-90');
                arrow.classList.remove('-mb-2');
                arrow.classList.add('-mt-2');
            }
        }

        function updateCommentaireSociete(element) {
            const societeId = element.dataset.societeId; // Récupère l'ID de la société
            const commentaireTexte = element.value; // Récupère la valeur du commentaire

            // Envoie la requête AJAX avec fetch
            fetch('/societe/' + societeId + '/commentaire/save', {
                    method: 'PATCH', // Utilise la méthode PATCH pour mettre à jour
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Envoie le token CSRF pour la sécurité
                    },
                    body: JSON.stringify({
                        commentaire: commentaireTexte, // Envoie le texte du commentaire
                    }),
                })
                .then(response => response.json()) // Récupère la réponse en JSON
                .then(data => {
                    if (!(data.message == 'Commentaire inchangé')) {
                        showFlashMessageFromJs(data.message, 2000);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du commentaire', error);
                });
        }

        function updateCommentaireEtablissement(element) {
            const etablissementId = element.dataset.etablissementId; // Récupère l'ID de l'établissement
            const commentaireTexte = element.value;

            // Envoie la requête AJAX avec fetch
            fetch('/societe/etablissement/' + etablissementId + '/commentaire/save', {
                    method: 'PATCH', // Utilise la méthode PATCH pour mettre à jour
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Envoie le token CSRF pour la sécurité
                    },
                    body: JSON.stringify({
                        commentaire: commentaireTexte, // Envoie le texte du commentaire
                    }),
                })
                .then(response => response.json()) // Récupère la réponse en JSON
                .then(data => {
                    if (!(data.message == 'Commentaire inchangé')) {
                        showFlashMessageFromJs(data.message, 2000);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du commentaire', error);
                });
        }
    </script>


</x-app-layout>
