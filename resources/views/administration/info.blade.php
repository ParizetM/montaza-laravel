<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('administration.index') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">
                {{ __('Administration') }}
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">
                >>
                </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('informations entreprise') }}
            </h2>
            <select name="choix_entreprise" id="choix_entreprise" class="select w-1/3">
                @foreach ($entites as $entite_select)
                    <option value="{{ $entite_select->id }}" {{ $entite_select->id == $entite->id ? 'selected' : '' }} onchange="change_entreprise()">
                        {{ $entite_select->name }}</option>
                @endforeach
            </select>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 flex">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 gap-4 w-full flex flex-col">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('informations entreprise') }}
                </h2>

                <div>
                    <x-input-label for="name" value="{{ __('Nom') }}" />
                    <x-text-input id="name" class="block mt-1 w-1/3" type="text" name="name" value="{{ old('name', $entite->name) }}"
                        required autofocus />
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="adresse" value="{{ __('Adresse') }}" />
                    <x-text-input id="adresse" class="block mt-1 w-1/3" type="text" name="adresse" value="{{ old('adresse', $entite->adresse) }}"
                        required />
                    @error('adresse')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="ville" value="{{ __('Ville') }}" />
                    <x-text-input id="ville" class="block mt-1 w-1/3" type="text" name="ville" value="{{ old('ville', $entite->ville) }}"
                        required />
                    @error('ville')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="code_postal" value="{{ __('Code Postal') }}" />
                    <x-text-input id="code_postal" class="block mt-1 w-1/3" type="text" name="code_postal" value="{{ old('code_postal', $entite->code_postal) }}"
                        required />
                    @error('code_postal')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="tel" value="{{ __('Téléphone') }}" />
                    <x-text-input id="tel" class="block mt-1 w-1/3" type="text" name="tel" value="{{ old('tel', $entite->tel) }}"
                        required />
                    @error('tel')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="siret" value="{{ __('SIRET') }}" />
                    <x-text-input id="siret" class="block mt-1 w-1/3" type="text" name="siret" value="{{ old('siret', $entite->siret) }}"
                        required />
                    @error('siret')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="rcs" value="{{ __('RCS') }}" />
                    <x-text-input id="rcs" class="block mt-1 w-1/3" type="text" name="rcs" value="{{ old('rcs', $entite->rcs) }}"
                        required />
                    @error('rcs')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="numero_tva" value="{{ __('Numéro TVA') }}" />
                    <x-text-input id="numero_tva" class="block mt-1 w-1/3" type="text" name="numero_tva" value="{{ old('numero_tva', $entite->numero_tva) }}"
                        required />
                    @error('numero_tva')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="code_ape" value="{{ __('Code APE') }}" />
                    <x-text-input id="code_ape" class="block mt-1 w-1/3" type="text" name="code_ape" value="{{ old('code_ape', $entite->code_ape) }}"
                        required />
                    @error('code_ape')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-input-label for="logo" value="{{ __('Logo') }}" />
                    <input id="logo" class="block mt-1 w-1/3 input-file" type="file" name="logo" accept="image/*" />
                    @error('logo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
