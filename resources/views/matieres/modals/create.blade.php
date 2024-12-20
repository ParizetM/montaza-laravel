<div class="p-2">
    <a x-on:click="$dispatch('close')">
        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
    </a>
    <div class="p-6 ">
        <form method="POST" action="{{ route('societes.contacts.store') }}" class="w-full" id="quick-create-form">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="societe_id_quick_create"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Société</label>
                    <select name="societe_id" id="societe_id_quick_create" class=" select mt-1 block w-full form-select"
                        required>
                        <option value="" disabled selected>Sélectionnez une société</option>
                        @foreach ($societes as $societe)
                            <option value="{{ $societe->id }}">{{ $societe->raison_sociale }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="etablissement_id_quick_create"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Établissement</label>
                    <select name="etablissement_id" id="etablissement_id_quick_create"
                        class="select mt-1 block w-full form-select" required>
                        <option value="" selected disabled>Sélectionnez d'abord une société </option>
                    </select>
                </div>
                <div class="col-span-2 ">
                    <x-input-label for="nom"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</x-input-label>
                    <x-text-input type="text" name="nom" id="nom" class="mt-1 block w-full form-input"
                        required />
                </div>

                <div>
                    <x-input-label for="fonction"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poste</x-input-label>
                    <x-text-input type="text" name="fonction" id="fonction" class="mt-1 block w-full form-input" />
                </div>
                <div>
                    <x-input-label for="email"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</x-input-label>
                    <x-text-input type="email" name="email" id="email" class="mt-1 block w-full form-input"
                        required />
                </div>
                <div>
                    <x-input-label for="telephone_fixe"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone
                        fixe</x-input-label>
                    <x-text-input type="tel" name="telephone_fixe" id="telephone_fixe"
                        class="mt-1 block w-full form-input" />
                </div>
                <div>
                    <x-input-label for="telephone_portable"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone
                        portable</x-input-label>
                    <x-text-input type="tel" name="telephone_portable" id="telephone_portable"
                        class="mt-1 block
                        w-full form-input" />
                </div>
                <div class="col-span-2">
                    <button type="submit" class="btn">Ajouter</button>
                </div>

            </div>
    </div>
    </form>
    <script class="SCRIPT">

    </script>
</div>
