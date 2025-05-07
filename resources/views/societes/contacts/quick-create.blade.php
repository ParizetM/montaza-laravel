<div class="p-2">
    <a x-on:click="$dispatch('close')">
        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
    </a>
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ajouter un Contact</h2>
        <form method="POST" action="{{ route('societes.contacts.store') }}"
              onsubmit="handleContactFormSubmit(event)"
              class="w-full text-gray-900 dark:text-gray-100" id="quick-create-form">
            @csrf

            {{-- SOCIÉTÉ ET ÉTABLISSEMENT --}}
            <div class="mb-4 flex">
                <div class="mb-4 w-full mr-2">
                    <x-input-label for="societe_id_quick_create">Société</x-input-label>
                    <select name="societe_id" id="societe_id_quick_create" class="select" required
                            onchange="updateEtablissementSelect(this.value)">
                        @if (isset($selected_societe))
                            <option value="{{ $selected_societe->id }}" selected>{{ $selected_societe->raison_sociale }}</option>
                        @else
                            <option value="" disabled selected>Sélectionner une société</option>
                        @endif
                        @foreach ($societes as $societe)
                            @if (isset($selected_societe) && $societe->id == $selected_societe->id)
                                @continue
                            @endif
                            <option value="{{ $societe->id }}">{{ $societe->raison_sociale }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 w-full">
                    <x-input-label for="etablissement_id_quick_create">Établissement</x-input-label>
                    <select name="etablissement_id" id="etablissement_id_quick_create" class="select" required>
                        @if (isset($selected_societe, $selected_etablissement))
                            <option value="{{ $selected_etablissement->id }}" selected>{{ $selected_etablissement->nom }}</option>
                        @else
                            <option value="" disabled selected>Sélectionner d'abord une société</option>
                        @endif
                    </select>
                </div>
            </div>

            {{-- INFORMATIONS CONTACT --}}
            <div class="mb-4">
                <x-input-label for="nom">Nom</x-input-label>
                <x-text-input type="text" name="nom" id="nom" class="mt-1 block w-full" required />
            </div>

            <div class="mb-4 flex">
                <div class="mb-4 w-full mr-2">
                    <x-input-label for="fonction">Poste</x-input-label>
                    <x-text-input type="text" name="fonction" id="fonction" class="mt-1 block w-full" />
                </div>
                <div class="mb-4 w-full">
                    <x-input-label for="email">Email</x-input-label>
                    <x-text-input type="email" name="email" id="email" class="mt-1 block w-full" required />
                </div>
            </div>

            <div class="mb-4 flex">
                <div class="mb-4 w-full mr-2">
                    <x-input-label for="telephone_fixe">Téléphone fixe</x-input-label>
                    <x-text-input type="tel" name="telephone_fixe" id="telephone_fixe" class="mt-1 block w-full" />
                </div>
                <div class="mb-4 w-full">
                    <x-input-label for="telephone_portable">Téléphone portable</x-input-label>
                    <x-text-input type="tel" name="telephone_portable" id="telephone_portable" class="mt-1 block w-full" />
                </div>
            </div>

            <div class="flex justify-between">
                <button type="button" class="btn" x-on:click="$dispatch('close')">Annuler</button>
                <button type="submit" class="btn">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<script class="SCRIPT">
    function handleContactFormSubmit(event) {
        event.preventDefault();
        var form = event.target;
        var formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fermer le modal
                document.querySelector('[x-on\\:click="$dispatch(\'close\')"]').click();
                // Afficher le message
                showFlashMessageFromJs('Contact ajouté avec succès', 2000, 'success');
            } else {
                showFlashMessageFromJs('Erreur lors de la création du contact', 2000, 'error');
                console.error('Erreur lors de la création du contact :', data);
            }
        })
        .catch(error => {
            showFlashMessageFromJs('Erreur lors de la création du contact', 2000, 'error');
            console.error('Erreur lors de la création du contact :', error);
        });
    }

    function updateEtablissementSelect(societeId) {
        var etablissementSelect = document.getElementById('etablissement_id_quick_create');

        // Efface les anciennes options
        etablissementSelect.innerHTML = '<option value="" disabled selected>Sélectionner un établissement</option>';

        if (societeId) {
            fetch(`/societe/${societeId}/etablissements/json`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(etablissement => {
                        var option = document.createElement('option');
                        option.value = etablissement.id;
                        option.textContent = etablissement.nom;
                        etablissementSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des établissements :', error);
                });
        }
    }
</script>
