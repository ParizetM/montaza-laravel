<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un établissement') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <form method="POST" action="{{ route('etablissements.store') }}"
                        class="flex flex-col w-full grid-cols-6 gap-6 sm:grid">
                        @csrf
                        <div class="col-span-6">
                            <x-input-label for="nom" :value="__('Nom établissement')" />
                            <x-text-input id="nom_etablissement" class="block mt-1 w-1/3" type="text" name="nom"
                                value="{{ old('nom') }}" required placeholder="Siége social" />
                            @error('nom')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <x-input-label for="adresse" :value="__('Adresse')" />
                            <x-text-input id="adresse" class=" mt-1 w-full" type="text" name="adresse" placeholder=" (Optionnel) 1 Rue de la Cité Nouvelle"
                                value="{{ old('adresse') }}" />
                            @error('adresse')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-3 ">
                            <x-input-label for="code_postal" :value="__('Code Postal')" />
                            <x-text-input id="code_postal" class="block mt-1 w-1/3" type="text" name="code_postal" placeholder=" (Optionnel) 44570"
                                value="{{ old('code_postal') }}" />
                            @error('code_postal')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-1 ">
                            <x-input-label for="ville" :value="__('Ville')" />
                            <x-text-input id="ville" class="block mt-1 w-full" type="text" name="ville" placeholder="(Optionnel) Trignac"
                                value="{{ old('ville') }}" />
                            @error('ville')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        


                        <div class="col-span-2">
                            <x-input-label for="region" :value="__('Région')" />
                            <x-text-input id="region" class="block mt-1 w-full" type="text" name="region" placeholder="(Optionnel) Pays de la Loire"
                                value="{{ old('region') }}" />
                            @error('region')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-4 ">
                            <x-input-label for="pays_id" :value="__('Pays')" />
                            <select name="pays_id" id="pays_id" class="select mt-1 w-1/2" required>
                                @foreach ($pays as $pay)
                                    @if ($pay->nom == 'France')
                                        <option value="{{ $pay->id }}" {{ old('pay_id') == $pay->id ? 'selected' : '' }}>
                                            {{ $pay->nom }}
                                        </option>
                                    @endif
                                @endforeach
                                @foreach ($pays as $pay)
                                    @if ($pay->nom != 'France')
                                        <option value="{{ $pay->id }}" {{ old('pay_id') == $pay->id ? 'selected' : '' }}>
                                            {{ $pay->nom }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('pays_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="col-span-2 ">
                            <x-input-label for="societe_id" :value="__('Société')" />
                            <select name="societe_id" id="societe_id" class="select mt-1 w-full" required>
                                <option value="" disabled {{ old('societe_id') == '' ? 'selected' : '' }}>--
                                    Choisir une société --</option>
                                <optgroup label="Clients">
                                    @foreach ($societes->where('societe_type_id', 1) as $societe)
                                        <option value="{{ $societe->id }}"
                                            {{ old('societe_id', $societe_->id ?? '') == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->raison_sociale }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Fournisseurs">
                                    @foreach ($societes->where('societe_type_id', 2) as $societe)
                                        <option value="{{ $societe->id }}"
                                            {{ old('societe_id', $societe_->id ?? '') == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->raison_sociale }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Clients & Fournisseurs">
                                    @foreach ($societes->where('societe_type_id', 3) as $societe)
                                        <option value="{{ $societe->id }}"
                                            {{ old('societe_id', $societe_->id ?? '') == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->raison_sociale }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('societe_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-2 ">
                            <x-input-label for="siret" :value="__('SIRET')" />
                            <x-text-input id="siret" class="block mt-1 w-full" type="text" name="siret" maxlength="15" minlength="14"
                                value="{!! old('siret',$societe_->siren.'&nbsp;') !!}" required />
                            @error('siret')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <x-input-label for="commentaire" :value="__('Commentaire')" />
                            <textarea rows="3" id="commentaire" name="commentaire"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100"
                                placeholder="(Optionnel) Votre commentaire ici">{{ old('commentaire') }}</textarea>
                            @error('commentaire')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end col-span-3 mt-4">
                            <button type="submit" class="btn ml-4">
                                {{ __('Créer') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function Autosiren() {
                var societeId = document.getElementById('societe_id').value;
                if (societeId) {
                    fetch(`/societe/${societeId}/json`)
                        .then(response => response.json())
                        .then(data => {
                                if (data.societe_type_id == 2) {
                                    const siretField = document.getElementById('siret');
                                    const siretLabel = document.querySelector('label[for="siret"]');

                                    siretField.required = false;
                                    siretLabel.querySelector('small')?.remove(); // Remove the "(Optionnel)" label if it exists
                                    const optionalSiretLabel = document.createElement('small');
                                    optionalSiretLabel.textContent = '(Optionnel)';
                                    siretLabel.appendChild(optionalSiretLabel);
                                } else {
                                    const siretField = document.getElementById('siret');
                                    const siretLabel = document.querySelector('label[for="siret"]');

                                    siretField.required = true;
                                    const optionalSiretLabel = siretLabel.querySelector('small');
                                    if (optionalSiretLabel) {
                                        optionalSiretLabel.remove(); // Remove the "(Optionnel)" label if it exists
                                    }
                                }
                                if (data.siren) {
                                    document.getElementById('siret').value = data.siren+' ';
                                } else {
                                    document.getElementById('siret').value = '';
                                }
                        })
                        .catch(error => console.error('Error fetching SIREN:', error));
                }
        }

        document.addEventListener('DOMContentLoaded', function() {
            Autosiren();
            document.getElementById('societe_id').addEventListener('change', function() {
                Autosiren();
            });
            document.getElementById('code_postal').addEventListener('input', function() {
                const codePostal = this.value;
                if (codePostal.length === 5) {
                    fetch(`https://geo.api.gouv.fr/communes?codePostal=${codePostal}&boost=population`)
                        .then(response => response.json())
                        .then(data => {
                            const villeField = document.getElementById('ville');
                            if (data.length > 0) {
                                villeField.value = data[0].nom; // Set the first city name
                                if (data.length > 1) {
                                    console.warn('Multiple cities found for this postal code. Using the first one.');
                                }
                            } else {
                                console.warn('No cities found for this postal code.');
                            }
                        })
                        .catch(error => console.error('Error fetching city data:', error));
                }
            });
        });
    </script>
</x-app-layout>
