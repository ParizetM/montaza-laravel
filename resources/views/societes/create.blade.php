<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer une société') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <form method="POST" action="{{ route('societes.store') }}"
                        class="flex flex-col w-full grid-cols-3 gap-6 sm:grid">
                        @csrf
                        <div class="col-span-3">
                            <x-input-label for="raison_sociale" :value="__('Raison Sociale')" />
                            <x-text-input id="raison_sociale" class="block mt-1 w-1/3" type="text" placeholder="Atlantis Montaza"
                                name="raison_sociale" value="{{ old('raison_sociale') }}" required autofocus />
                            @error('raison_sociale')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="forme_juridique_id" :value="__('Forme Juridique')" />
                            <select id="forme_juridique_id" name="forme_juridique_id" class="block mt-1 w-full select" required>
                                <option value="" disabled {{ old('forme_juridique_id') == '' ? 'selected' : '' }}>-- Choisir une forme juridique --</option>
                                @foreach ($formeJuridiques as $formeJuridique)
                                    <option value="{{ $formeJuridique->id }}" {{ old('forme_juridique_id') == $formeJuridique->id ? 'selected' : '' }}>
                                        {{ $formeJuridique->code }} {{ $formeJuridique->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('forme_juridique_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="code_ape_id" :value="__('Code APE')" />
                            <select id="code_ape_id" name="code_ape_id" class="block mt-1 w-full select" required>
                                <option value="" disabled {{ old('code_ape_id') == '' ? 'selected' : '' }}>-- Choisir un code APE --</option>
                                @foreach ($codeApes as $codeApe)
                                    <option value="{{ $codeApe->id }}" {{ old('code_ape_id') == $codeApe->id ? 'selected' : '' }}>
                                        {{ $codeApe->code }} {{ $codeApe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_ape_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="societe_type_id" :value="__('Type de Société')" />
                            <select id="societe_type_id" name="societe_type_id" class="block mt-1 w-full select" required>
                                <option value="" disabled {{ old('societe_type_id') == '' ? 'selected' : '' }}>-- Choisir un type de société --</option>
                                @foreach ($societeTypes as $societeType)
                                    <option value="{{ $societeType->id }}" {{ old('societe_type_id') == $societeType->id ? 'selected' : '' }}>
                                        {{ $societeType->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('societe_type_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="telephone" :value="__('Téléphone')" />
                            <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" maxlength="30"
                                placeholder="+33 XX XX XX XX XX" value="{{ old('telephone') }}" required />
                            @error('telephone')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                placeholder="info@atlantismontaza.fr" value="{{ old('email') }}" required />
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="site_web" :value="__('Site Web')" />
                            <x-text-input id="site_web" class="block mt-1 w-full" type="text" name="site_web"
                                placeholder="(Optionnel) https://www.exemle.com" value="{{ old('site_web') }}" />
                            @error('site_web')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="siren" :value="__('SIREN')" />
                            <x-text-input id="siren" class="block mt-1 w-full" type="text" name="siren" maxlength="9"
                                placeholder="XXXXXXXXX" value="{{ old('siren') }}" required />
                            @error('siren')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="numero_tva" :value="__('Numéro TVA')" />
                            <x-text-input id="numero_tva" class="block mt-1 w-full" type="text" name="numero_tva" maxlength="13"
                                placeholder="FRXX XXXX XXXX" value="{{ old('numero_tva') }}" required />
                            @error('numero_tva')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-3">
                            <x-input-label for="commentaire" :value="__('Commentaire')" />
                            <textarea rows="3" id="commentaire" name="commentaire"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100"
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
</x-app-layout>
