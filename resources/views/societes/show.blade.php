<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('societes.index') }}"
                class="flex px-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 items-center h-full rounded-sm">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Sociétés') }}
                </h2>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __(' >>&nbsp;') !!}
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $societe->raison_sociale }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Carte d'information principale de la société -->
                <div class="w-full lg:w-3/5">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                        <!-- En-tête société -->
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center p-6">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                        {{ $societe->raison_sociale }}
                                    </h1>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $societe->societeType->nom }}
                                    </p>
                                </div>
                                @can('gerer_les_societes')
                                    <a href="{{ route('societes.edit', ['societe' => $societe->id]) }}"
                                        class="btn flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Modifier
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <!-- Informations générales -->
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-2">
                                {{ __('Informations Générales') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Forme Juridique') }}</p>
                                        <div class="flex items-center">
                                            <x-copiable_text text="{{ $societe->formeJuridique->code }}" />
                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                ({{ $societe->formeJuridique->nom }})
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('SIREN') }}</p>
                                        <x-copiable_text text="{{ $societe->siren }}" />
                                    </div>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Condition de paiement') }}</p>
                                        <x-copiable_text text="{{ $societe->conditionPaiement->nom }}" />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Code APE') }}</p>
                                        <div class="flex items-center">
                                            <x-copiable_text text="{{ $societe->codeApe->code ?? '' }}" />
                                            @if($societe->codeApe)
                                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                    ({{ $societe->codeApe->nom }})
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Numéro TVA') }}</p>
                                        <x-copiable_text text="{{ $societe->numero_tva }}" />
                                    </div>
                                </div>
                            </div>

                            <!-- Contact -->
                            <h3 class="text-lg font-semibold my-4 text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-2">
                                {{ __('Contact') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Téléphone') }}</p>
                                    <a href="tel:{{ $societe->telephone }}"
                                       class="flex items-center text-blue-500 dark:text-blue-400 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                        </svg>
                                        {{ $societe->telephone }}
                                    </a>
                                </div>

                                <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Email') }}</p>
                                    <a href="mailto:{{ $societe->email }}"
                                       class="flex items-center text-blue-500 dark:text-blue-400 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                        {{ $societe->email }}
                                    </a>
                                </div>

                                <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Site Web') }}</p>
                                    <a href="{{ $societe->site_web }}" target="_blank"
                                       class="flex items-center text-blue-500 dark:text-blue-400 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $societe->site_web }}
                                    </a>
                                </div>
                            </div>

                            <!-- Commentaire -->
                            <h3 class="text-lg font-semibold my-4 text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-2">
                                {{ __('Commentaire') }}
                            </h3>
                            <div class="bg-gray-50 dark:bg-gray-750 p-2 rounded-md">
                                <textarea rows="3" id="commentaire" name="commentaire"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                                    data-societe-id="{{ $societe->id }}" onblur="updateCommentaireSociete(this)">{{ $societe->commentaire ? $societe->commentaire->contenu : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{--
######## ########    ###    ########  ##       ####  ######   ######  ######## ##     ## ######## ##    ## ########  ######
##          ##      ## ##   ##     ## ##        ##  ##    ## ##    ## ##       ###   ### ##       ###   ##    ##    ##    ##
##          ##     ##   ##  ##     ## ##        ##  ##       ##       ##       #### #### ##       ####  ##    ##    ##
######      ##    ##     ## ########  ##        ##   ######   ######  ######   ## ### ## ######   ## ## ##    ##     ######
##          ##    ######### ##     ## ##        ##        ##       ## ##       ##     ## ##       ##  ####    ##          ##
##          ##    ##     ## ##     ## ##        ##  ##    ## ##    ## ##       ##     ## ##       ##   ###    ##    ##    ##
########    ##    ##     ## ########  ######## ####  ######   ######  ######## ##     ## ######## ##    ##    ##     ######
--}}
                <div class="w-full lg:w-2/5">
                    @if (isset($etablissement))
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700" id="etablissements">
                            <!-- En-tête établissements -->
                            <div class="border-b border-gray-200 dark:border-gray-700">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                                            {{ __('Établissements') }}
                                        </h2>
                                        @can('gerer_les_societes')
                                            <a href="{{ route('etablissements.edit', ['societe' => $societe->id, 'etablissement' => $etablissement->id ?? 0]) }}"
                                                class="btn-sm flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier
                                            </a>
                                        @endcan
                                    </div>

                                    <!-- Alerte si aucun contact -->
                                    @if ($etablissement->societeContacts->count() == 0)
                                        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-400 dark:border-red-800 p-4 mb-4 rounded">
                                            <div class="flex items-center">
                                                <x-icon :size="1" type="error_icon" class="icons-no_hover fill-red-500 dark:fill-red-400 mr-2" />
                                                <p class="text-sm text-red-500 dark:text-red-400">
                                                    Aucun contact n'est actuellement associé à cet établissement.
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Sélecteur d'établissement -->
                                    <div class="flex items-center">
                                        <select name="etablissement_id" id="etablissement_id" class="select-left w-full"
                                            onchange="changeEtablissement(this)">
                                            @foreach ($societe->etablissements as $etablissement_select)
                                                <option value="{{ $etablissement_select->id }}"
                                                    {{ $etablissement_select->id == $etablissement->id ? 'selected' : '' }}>
                                                    {{ $etablissement_select->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <a href="{{ route('etablissements.create', ['societe' => $societe->id]) }}"
                                            class="btn-select-right">+</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Information de l'établissement -->
                            <div class="p-6">
                                <!-- En-tête avec nom établissement -->
                                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                        {{ $etablissement->nom }}
                                    </h3>

                                    <div class="flex gap-2">
                                        <button type="button" class="btn-sm" title="Contacts"
                                            x-data="" x-on:click.prevent="$dispatch('open-modal', 'contacts-modal-{{ $etablissement->id }}')">
                                            <x-icon :size="1" type="contact" class="icons-no_hover" />
                                        </button>

                                        <button type="button" class="btn-sm" title="Ajouter un contact"
                                            x-data="" x-on:click.prevent="$dispatch('open-modal', 'etablissement-quick-contact')">
                                            <x-icons.new-contact class="icons-no_hover" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Détails de l'établissement -->
                                <div class="space-y-3 mb-4">
                                    <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Adresse') }}</p>
                                        <x-copiable_text text="{{ $etablissement->adresse }}" />
                                        @if ($etablissement->complement_adresse)
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Complément d\'adresse') }}</p>
                                                <x-copiable_text text="{{ $etablissement->complement_adresse }}" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Code postal') }}</p>
                                            <x-copiable_text text="{{ $etablissement->code_postal }}" />
                                        </div>

                                        <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Ville') }}</p>
                                            <x-copiable_text text="{{ $etablissement->ville }}" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Région') }}</p>
                                            <x-copiable_text text="{{ $etablissement->region }}" />
                                        </div>

                                        <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Pays') }}</p>
                                            <x-copiable_text text="{{ $etablissement->pays->nom }}" />
                                        </div>
                                    </div>

                                    <div class="p-3 bg-gray-50 dark:bg-gray-750 rounded-md">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('SIRET') }}</p>
                                        <x-copiable_text text="{{ $etablissement->siret }}" />
                                    </div>
                                </div>

                                <!-- Commentaire établissement -->
                                <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-2">
                                    {{ __('Commentaire') }}
                                </h3>
                                <div class="bg-gray-50 dark:bg-gray-750 p-2 rounded-md">
                                    <textarea rows="3" id="commentaire" name="commentaire"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                                        data-etablissement-id="{{ $etablissement->id }}" onblur="updateCommentaireEtablissement(this)">{{ $etablissement->commentaire ? $etablissement->commentaire->contenu : '' }}</textarea>
                                </div>
                            </div>

                            <!-- Modals pour les contacts -->
                            @php
                                $contacts = $etablissement->societeContacts;
                            @endphp
                            <x-modals.contacts name="contacts-modal-{{ $etablissement->id }}" :contacts="$contacts" />
                            <x-modal name="etablissement-quick-contact" focusable maxWidth="5xl">
                                @include('societes.contacts.quick-create', [
                                    'societes' => $societes,
                                    'selected_societe' => $societe,
                                    'selected_etablissement' => $etablissement,
                                    'reload_after_submit' => true,
                                ])
                            </x-modal>
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700" id="etablissements">
                            <div class="p-6 flex flex-col items-center justify-center h-full text-center">
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-6 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">
                                    Aucun établissement
                                </h2>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">
                                    Cette société ne possède actuellement aucun établissement.
                                </p>
                                <a href="{{ route('etablissements.create', ['societe' => $societe->id]) }}"
                                    class="btn flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Ajouter un établissement
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateCommentaireSociete(element) {
            const societeId = element.dataset.societeId;
            const commentaireTexte = element.value;

            fetch('/societe/' + societeId + '/commentaire/save', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        commentaire: commentaireTexte,
                    }),
                })
                .then(response => response.json())
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
            const etablissementId = element.dataset.etablissementId;
            const commentaireTexte = element.value;

            fetch('/societe/etablissement/' + etablissementId + '/commentaire/save', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        commentaire: commentaireTexte,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (!(data.message == 'Commentaire inchangé')) {
                        showFlashMessageFromJs(data.message, 2000);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du commentaire', error);
                });
        }

        function changeEtablissement(element) {
            const etablissementId = element.value;
            window.location.href = '/societe/{{ $societe->id }}/etablissement/' + etablissementId;
            const etablissement = document.getElementById('etablissements');
            const etablissementHeight = etablissement.offsetHeight;
            etablissement.innerHTML =
                '<div id="loading-spinner" class="inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50" style="height: ' +
                etablissementHeight +
                'px;"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style>';
        }
    </script>
</x-app-layout>
