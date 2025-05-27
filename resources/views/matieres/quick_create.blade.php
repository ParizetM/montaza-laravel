<div class="p-2">
    <a x-on:click="$dispatch('close')">
        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
    </a>
    <div class="p-6 ">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ajouter une Matière</h2>
        <form method="POST" action="{{ route('matieres.quickStore', $modal_id) }}" onsubmit="handleFormSubmit(event)"
            class="w-full text-gray-900 dark:text-gray-100" id="quick-create-form">
            @csrf
            <div class="mb-4 flex">
                <div class="mb-4">
                    <x-input-label for="ref_interne" :value="__('référence interne')" />
                    <x-text-input type="text" name="ref_interne" id="ref_interne" class="mt-1 block w-full" required
                        value="{{ $last_ref }}" />
                </div>
                <div class="mb-4 ml-2 flex-grow">
                    <x-input-label for="societe_id" :value="__('référence externe')" />
                    <div class="flex w-full">
                        <select name="societe_id" id="societe_id-{{ $modal_id }}"
                            class="mt-1 py-3 select-left rounded-r-none" required>
                        <option value="" disabled selected>Sélectionner un fournisseur</option>
                            @foreach ($societes as $societe)
                                <option value="{{ $societe->id }}">{{ $societe->raison_sociale }}</option>
                            @endforeach
                        </select>
                        <x-text-input type="text" name="ref_externe" id="ref_externe"
                            class="mt-1 block w-full rounded-l-none" placeholder="Référence" />
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <x-input-label for="designation" :value="__('Désignation')" />
                <x-text-input type="text" name="designation" id="designation" class="mt-1 block w-full" required />
            </div>
            {{-- FAMILLE --}}
            <div class="mb-4 flex">
                <div class="mb-4 w-full mr-2">
                    <x-input-label for="famille_id-{{ $modal_id }}"> Famille</x-input-label>
                    <select name="famille_id" id="famille_id-{{ $modal_id }}" class="select" required
                        onchange="updateSousFamilleSelect(this.value)">
                        <option value="" disabled selected>Sélectionner une famille</option>
                        @foreach ($familles as $famille)
                            <option value="{{ $famille->id }}">{{ $famille->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 w-full">
                    <x-input-label for="sous_famille_id-{{ $modal_id }}">Sous Famille</x-input-label>
                    <div class="flex">
                        <select name="sous_famille_id" id="sous_famille_id-{{ $modal_id }}"
                            class="select-left w-full" required>
                            <option value="" disabled selected>Sélectionner d'abord une famille</option>
                        </select>

                        <button class="btn-select-right" x-data id="addSousFamille-{{ $modal_id }}" disabled
                            x-on:click.prevent="$dispatch('open-modal', 'addSousFamille-{{ $modal_id }}')"><x-icons.add /></button>

                    </div>
                </div>
            </div>
            {{-- STANDARD --}}
            <div class="mb-4 flex">
                <div class="mb-4 w-fit mr-2">
                    <x-input-label for="dossier_standard_id-{{ $modal_id }}" optionnel
                        class="whitespace-nowrap">Dossier Standard</x-input-label>
                    <select name="dossier_standard_id" id="dossier_standard_id-{{ $modal_id }}" class="select"
                        onchange="updateStandardSelect(this.value)">
                        <option value="" disabled selected>Sélectionner un dossier</option>
                        @foreach ($dossier_standards as $dossier)
                            <option value="{{ $dossier->nom }}">{{ $dossier->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 w-fit mr-2">
                    <x-input-label for="standard_id-{{ $modal_id }}" optionnel>Standard</x-input-label>
                    <select name="standard_id" id="standard_id-{{ $modal_id }}" class="select w-fit"
                        onchange="updateVersionSelect(this.value)">
                        <option value="" disabled selected>Sélectionner d'abord un dossier</option>
                    </select>
                </div>
                <div class="mb-4 w-fit">
                    <x-input-label for="standard_version_id-{{ $modal_id }}" optionnel>Rév</x-input-label>
                    <div class="flex">
                        <select name="standard_version_id" id="standard_version_id-{{ $modal_id }}"
                            class="select w-fit">
                        </select>
                        <a href="{{ route('standards.create') }}" target="_blank"
                            type="button" class="btn-select-right" title="Ajouter un Standard">
                            <x-icons.add class="icons_no_hover" size="1"  />
                        </a>
                    </div>
                </div>
            </div>
            <div class="mb-4 flex">
                <div class=" mr-2">
                    <div class="flex items-center">
                        <x-input-label for="quantite" value="{{ __('Quantité') }}" />
                        <small class="text-xs ml-1">(stock actuel)</small>
                    </div>
                    <x-text-input type="number" name="quantite" id="quantite" class="mt-1 block" value="0"
                        required />
                </div>
                <div class=" mr-2">
                    <div class="w-full flex">
                        <x-input-label for="ref_valeur_unitaire-{{ $modal_id }}"
                            value="{{ __('Valeur Réf Unitaire') }}" />
                        <x-tooltip position="left">
                            <x-slot name="slot_item">
                                <x-icons.question class="icons" size="1" />
                            </x-slot>
                            <x-slot name="slot_tooltip">
                                <p class="text-sm font-bold">Valeur de référence unitaire de la matière</p>
                                <p class="text-sm">Exemple: Longueur standard de stockage, comme 6m ou 12m pour un
                                    tuyau.</p>
                            </x-slot>
                        </x-tooltip>
                    </div>
                    <x-no-or-number name="ref_valeur_unitaire" id="ref_valeur_unitaire-{{ $modal_id }}" required
                        value="non" class="mt-1" />
                </div>
                <div class="w-1/4">
                    <x-input-label for="unite_id">Unité</x-input-label>
                    <select name="unite_id" id="unite_id" class="mt-1 py-3 select" required>
                        <option value="" disabled selected>Sélectionner</option>
                        @foreach ($unites as $unite)
                            <option value="{{ $unite->id }}" title="{{ $unite->full }}">{{ $unite->short }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <x-input-label for="dn" value="{{ __('DN') }}" optionnel />
                <x-text-input type="text" name="dn" id="dn" class="mt-1 block w-full" />
            </div>
            <div class="mb-4">
                <x-input-label for="epaisseur" value="{{ __('Épaisseur') }}" optionnel />
                <x-text-input type="text" name="epaisseur" id="epaisseur" class="mt-1 block w-full" />
            </div>
            <div class="mb-4">
                <x-input-label for="stock_min" value="{{ __('Stock Minimum') }}" />
                <x-text-input type="number" name="stock_min" id="stock_min" class="mt-1 block w-full"
                    value="0" required />
            </div>
            <div class="col-span-2 flex justify-between">
                <button type="button" class="btn" x-on:click="$dispatch('close')"
                    id="quick-create-matiere-cancel-{{ $modal_id }}">Annuler</button>
                <button type="submit" class="btn">Ajouter</button>
            </div>

        </form>
        {{-- FORMULAIRE AJOUTER SOUS FAMILLE --}}
        <x-modal name="addSousFamille-{{ $modal_id }}" id="addSousFamille-{{ $modal_id }}">
            <div class="p-2">
                <a x-on:click="$dispatch('close')">
                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                </a>
                <div class="p-6 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ajouter une Sous Famille</h2>
                    <form method="POST" action="{{ route('matieres.sous_familles.store') }}"
                        onsubmit="handleSousFamilleSubmit(event)" class="w-full text-gray-900 dark:text-gray-100">
                        <div class="mb-4">
                            <x-input-label for="addSousFamille-famille_id-{{ $modal_id }}">Famille</x-input-label>
                            <select name="famille_id" id="addSousFamille-famille_id-{{ $modal_id }}"
                                class="select">
                                @foreach ($familles as $famille)
                                    <option value="{{ $famille->id }}" disabled>{{ $famille->nom }}</option>
                                @endforeach
                            </select>
                            <select name="famille_id" id="addSousFamille-famille_id_hidden-{{ $modal_id }}"
                                class="hidden">
                                @foreach ($familles as $famille)
                                    <option value="{{ $famille->id }}">{{ $famille->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="nom" :value="__('Nom')" />
                            <x-text-input type="text" name="nom" id="nom" class="mt-1 block w-full"
                                required />
                        </div>
                        <div class="col-span-2 flex justify-between">
                            <button type="button" class="btn" x-on:click="$dispatch('close')"
                                id="addSousFamille-button_cancel-{{ $modal_id }}"> Annuler</button>
                            <button type="submit" class="btn">Ajouter</button>
                        </div>
                    </form>
                    <script class="SCRIPT">
                        function handleSousFamilleSubmit(event) {
                            event.preventDefault();
                            var form = event.target;
                            var formData = new FormData(form);
                            var url = form.action;

                            fetch(url, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        var sousFamilleSelect = document.getElementById('sous_famille_id-{{ $modal_id }}');
                                        var option = document.createElement('option');
                                        option.value = data.sousFamille.id;
                                        option.textContent = data.sousFamille.nom;
                                        sousFamilleSelect.appendChild(option);
                                        sousFamilleSelect.value = data.sousFamille.id;
                                        showFlashMessageFromJs('Sous Famille ajoutée avec succès !', 2000, 'success');
                                        document.getElementById('addSousFamille-button_cancel-{{ $modal_id }}').click();
                                    } else {
                                        showFlashMessageFromJs('Erreur lors de l\'ajout de la Sous Famille.', 2000, 'error');
                                    }
                                })
                                .catch(error => {
                                    showFlashMessageFromJs('Erreur lors de l\'ajout de la Sous Famille.', 2000, 'error');
                                    console.error('Erreur lors de l\'ajout de la Sous Famille :', error);
                                });
                        }
                    </script>
                </div>
            </div>
        </x-modal>
    </div>
</div>
<script class="SCRIPT">
    function updateSousFamilleSelect(familleId) {
        var sousFamilleSelect = document.getElementById('sous_famille_id-{{ $modal_id }}');
        sousFamilleSelect.innerHTML =
            '<option value="" disabled selected>Sélectionner une sous famille</option>';
        fetch(`/matieres/famille/${familleId}/sous-familles/json`)
            .then(response => response.json())
            .then(data => {
                data.forEach(sousFamille => {
                    var option = document.createElement('option');
                    option.value = sousFamille.id;
                    option.textContent = sousFamille.nom;
                    sousFamilleSelect.appendChild(option);
                });
                document.getElementById('addSousFamille-{{ $modal_id }}').disabled = false;
                document.getElementById('addSousFamille-famille_id-{{ $modal_id }}').value = familleId;
                document.getElementById('addSousFamille-famille_id_hidden-{{ $modal_id }}').value = familleId;
            })

            .catch(error => {
                console.error('Erreur lors de la récupération des sous familles :', error);
            });
    }


    function updateStandardSelect(dossierId) {
        var standardSelect = document.getElementById('standard_id-{{ $modal_id }}');
        standardSelect.innerHTML = '<option value="" disabled selected>Sélectionner un standard</option>';

        fetch(`/matieres/standards/${dossierId}/standards/json`)
            .then(response => response.json())
            .then(data => {
                data.forEach(standard => {
                    var option = document.createElement('option');
                    option.value = standard.nom;
                    option.textContent = standard.nom;
                    standardSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des standards :', error);
            });
    }

    function updateVersionSelect(standardId) {
        var versionSelect = document.getElementById('standard_version_id-{{ $modal_id }}');
        var dossierId = document.getElementById('dossier_standard_id-{{ $modal_id }}').value;
        var standard = document.getElementById('standard_id-{{ $modal_id }}').value;
        versionSelect.innerHTML = '';

        fetch(`/matieres/standards/${dossierId}/${standard}/versions/json`)
            .then(response => response.json())
            .then(data => {
                data.forEach(version => {
                    var option = document.createElement('option');
                    option.value = version;
                    option.textContent = version;
                    versionSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des versions :', error);
            });
    }

    function handleFormSubmit(event) {
        event.preventDefault();
        var form = event.target;
        var formData = new FormData(form);
        var url = form.action;

        fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFlashMessageFromJs('Matière ajoutée avec succès !', 2000, 'success');
                    document.getElementById('quick-create-matiere-cancel-{{ $modal_id }}').click();
                    // Check if a searchbar exists on the page
                    if (document.getElementById('searchbar')) {
                        // Get the designation from the form
                        var designation = formData.get('ref_interne') + ' ' + formData.get('designation');
                        // Get the searchbar element
                        var searchbar = document.getElementById('searchbar');

                        // Clear the current value
                        searchbar.value = '';

                        // Simulate typing the designation letter by letter
                        var i = 0;

                        function typeDesignation() {
                            if (i < designation.length) {
                                // Create and dispatch keyboard event
                                const event = new KeyboardEvent('keydown', {
                                    key: designation.charAt(i),
                                    code: 'Key' + designation.charAt(i).toUpperCase(),
                                    bubbles: true
                                });
                                searchbar.dispatchEvent(event);
                                // Also update the value
                                searchbar.value += designation.charAt(i);
                                // Trigger input event to ensure search functionality activates
                                searchbar.dispatchEvent(new Event('input', {
                                    bubbles: true
                                }));
                                i++;
                                setTimeout(typeDesignation, 50); // 50ms delay between each character
                            }
                        }

                        // Start typing after a small delay
                        setTimeout(typeDesignation, 300);
                    }
                } else {
                    showFlashMessageFromJs('Erreur lors de l\'ajout de la matière.', 2000, 'error');
                }
            })
            .catch(error => {
                showFlashMessageFromJs('Erreur lors de l\'ajout de la matière.', 2000, 'error');
                console.error('Erreur lors de l\'ajout de la matière :', error);
            });
    }
</script>
