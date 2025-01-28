<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                <a href="{{ route('cde.show', $cde->id) }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{!! __('Créer une commande') !!}</a>
                >> Validation
            </h2>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <form action="{{ route('cde.validate', $cde->id) }}" method="POST">
            @csrf
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
                <h1 class="text-3xl font-bold mb-6 text-center">{{ $cde->nom }}</h1>
                <div class="flex justify-between">
                    <div class="flex flex-col gap-4 m-4">
                        <div class="flex gap-4">
                            <div>
                                <div class="flex gap-4">
                                    <x-input-label value="Numéro d'affaire" />
                                    <small>(Optionnel)</small>
                                </div>
                                <x-text-input name="numero_affaire" :value="old('numero_affaire', $cde->numero_affaire)" />
                                @error('numero_affaire')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <div class="flex gap-4">
                                    <x-input-label value="Nom d'affaire" />
                                    <small>(Optionnel)</small>
                                </div>
                                <x-text-input name="nom_affaire" :value="old('nom_affaire', $cde->nom_affaire)" />
                                @error('nom_affaire')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <div class="flex gap-4">
                                    <x-input-label value="Numéro de devis" />
                                    <small>(Optionnel)</small>
                                </div>
                                <x-text-input name="numero_devis" :value="old('numero_devis', $cde->numero_devis)" />
                                @error('numero_devis')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="flex gap-4">
                                <x-input-label value="Affaire suivi par " />
                                <small>(Optionnel)</small>
                            </div>
                            <select name="affaire_suivi_par" required class="select w-fit min-w-96">
                                <option value="0" {{ old('affaire_suivi_par') == 0 ? 'selected' : '' }}>Non
                                    suivi</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('affaire_suivi_par') == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('affaire_suivi_par')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <div class="flex gap-4">
                                <x-input-label value="Acheteur " />
                                <small>(Optionnel)</small>
                            </div>
                            <select name="acheteur_id" required class="select w-fit min-w-96">
                                <option value="0"
                                    {{ old('acheteur_id', $cde->acheteur_id) == 0 ? 'selected' : '' }}>
                                    Sans Acheteur
                                </option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('acheteur_id', $cde->acheteur_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('acheteur_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex gap-4">
                            <x-toggle :checked="old('afficher_destinataire', true)" :label="'Afficher le mail du destinataire dans le PDF ?'" id="afficher_destinataire"
                                name="afficher_destinataire" class="toggle-class" />
                            @error('afficher_destinataire')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <div class="flex gap-4">
                                <x-input-label value="Date de rendu" />
                                <small>(Optionnel)</small>
                            </div>
                            <x-date-input name="date_rendu" :value="old('date_rendu')" />
                            @error('date_rendu')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <img src="{{ asset($entite->logo) }}" alt="Logo"
                        class="w-1/4 h-1/4 mb-4 object-contain float-right">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn">{{ __('Valider') }}</button>
                </div>
            </div>
        </form>
    </div>


</x-app-layout>
