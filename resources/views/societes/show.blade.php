<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Aperçu de la société') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 dark:bg-gray-900 flex flex-col sm:flex-row text-gray-700 dark:text-gray-100">


        <div class="max-w-5xl w-full sm:px-6 lg:px-8 mb-2">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- En-tête Société -->
                <div class=" py-6 px-8 text-gray-800 dark:text-gray-200">
                    <h1 class="text-3xl font-bold mb-1">
                        {{ $societe->raison_sociale }}
                    </h1>
                    <p class="text-lg">
                        {{ $societe->societeType->nom }}
                    </p>
                </div>

                <!-- Contenu Principal -->
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Informations Générales -->
                    <div class="col-span-1 md:col-span-2">
                        <h3 class="text-xl font-semibold mb-3 border-b-2 pb-2 text-gray-800 dark:text-gray-200">
                            {{ __('Informations Générales') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <strong class="text-gray-800 dark:text-gray-200">{{ __('Forme Juridique:') }}</strong>
                                <x-copiable_text text="{{ $societe->formeJuridique->code }}" />
                                    <small>({{ $societe->formeJuridique->nom }})</small>
                            </div>
                            <div>
                                <strong class="text-gray-800 dark:text-gray-200">{{ __('Code APE:') }}</strong>
                                <x-copiable_text text="{{ $societe->codeApe->code }}" />
                                    <small>({{ $societe->codeApe->nom }})</small>
                            </div>
                            <div>
                                <strong class="text-gray-800 dark:text-gray-200">{{ __('SIREN:') }}</strong>
                                <x-copiable_text text="{{ $societe->siren }}" />
                            </div>
                            <div>
                                <strong class="text-gray-800 dark:text-gray-200">{{ __('Numéro TVA:') }}</strong>
                                <x-copiable_text text="{{ $societe->numero_tva }}" />
                            </div>
                        </div>
                    </div>
                    <!-- Contacts -->
                    <div class="col-span-1">
                        <h3 class="text-xl font-semibold mb-3 border-b-2 pb-2 text-gray-800 dark:text-gray-200">
                            {{ __('Contacts') }}</h3>
                        <div class="space-y-3">
                            <div>
                                <strong class="text-gray-800 dark:text-gray-200">{{ __('Téléphone:') }}</strong>
                                <br>
                                <a href="mailto:{{ $societe->telephone }}" class=" text-blue-500 dark:text-blue-400 hover:underline whitespace-nowrap">{{ $societe->telephone }}</a>
                            </div>
                            <div>
                                <strong class="text-gray-800 dark:text-gray-200">{{ __('Email:') }}</strong>
                                <a href="mailto:{{ $societe->email }}" class=" text-blue-500 dark:text-blue-400 hover:underline whitespace-nowrap">{{ $societe->email }}</a>
                            </div>
                            <div>
                                <strong class="text-gray-800 dark:text-gray-200">{{ __('Site Web:') }}</strong>
                                <a href="{{ $societe->site_web }}"
                                    class="text-blue-500 dark:text-blue-400 hover:underline whitespace-nowrap">
                                    {{ $societe->site_web }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Commentaire -->
                    <div class="col-span-1 md:col-span-3">
                        <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">
                            {{ __('Commentaire') }}</h3>
                            <textarea rows="3" id="commentaire" name="commentaire"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100"
                            data-societe-id="{{ $societe->id }}" onblur="updateCommentaireSociete(this)">{{ $societe->commentaire ? $societe->commentaire->contenu : '' }}</textarea>

                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-xl w-full sm:pr-6 lg:pr-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- En-tête Etablissement -->
                <div class=" py-6 px-8 text-gray-800 dark:text-gray-200">
                    <h1 class="text-3xl font-bold mb-1">
                        {{ __('Établissements') }}
                    </h1>
                    <div class="flex mt-4">
                    <select name="etablissement_id" id="etablissement_id"
                        class="select-left w-full"
                        onchange="window.location.href = '/societe/{{ $societe->id }}/etablissement/' + this.value;">
                        @foreach ($societe->etablissements as $etablissement_select)
                            <option value="{{ $etablissement_select->id }}" {{ $etablissement_select->id == $etablissement->id ? 'selected' : '' }}>
                                {{ $etablissement_select->nom }}
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('etablissements.create', ['societe' => $societe->id]) }}"
                        class="btn-select-right dark:bg-gray-900">+</a>
                    </a>
                </div>
                </div>
                <div class="mx-8 flex justify-between border-b-2">
                 <h1 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $etablissement->nom }}
                </h1>
                <button type="button" class="btn mb-2 dark:bg-gray-900" title="Contacts"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'contacts-modal-{{ $etablissement->id }}')">
                            <x-icon :size="1.5" type="contact" class="icons-no_hover " />
                        </button>
                        @php
                            $contacts = $etablissement->societeContacts;
                        @endphp
                        <x-modals.contacts name="contacts-modal-{{ $etablissement->id }}" :contacts="$contacts" />
                </div>
                <!-- Contenu Principal -->
                <div class="px-8 pb-8 mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Informations Générales -->
                    <div class="col-span-3 md:col-span-3">
                        <div class="flex flex-col gap-3">
                            <x-copiable_text titre="Adresse : " text="{{ $etablissement->adresse }}" />
                            <x-copiable_text titre="Code postal : " text="{{ $etablissement->code_postal }}" />
                            <x-copiable_text titre="Ville : " text="{{ $etablissement->ville }}" />
                            <x-copiable_text titre="Région : " text="{{ $etablissement->region }}" />
                            <x-copiable_text titre="Pays : " text="{{ $etablissement->pays->nom }}" />
                            <x-copiable_text titre="Siret : " text="{{ $etablissement->siret }}" />
                        </div>
                    </div>
                <!-- Commentaire -->
                <div class="col-span-1 md:col-span-3">
                    <h3 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200 mt-1">
                        {{ __('Commentaire') }}</h3>
                        <textarea rows="3" id="commentaire" name="commentaire"
                        class=" block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100"
                        data-etablissement-id="{{ $etablissement->id }}" onblur="updateCommentaireEtablissement(this)">{{ $etablissement->commentaire ? $etablissement->commentaire->contenu : '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
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
