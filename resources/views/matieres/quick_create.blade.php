<div class="p-2">
    <a x-on:click="$dispatch('close')">
        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
    </a>
    <div class="p-6 ">
        <form method="POST" action="{{ route('societes.contacts.store') }}"
            class="w-full text-gray-900 dark:text-gray-100" id="quick-create-form">
            @csrf
            <div class="mb-4">
                <x-input-label for="designation" :value="__('Désignation')" />
                <x-text-input type="text" name="designation" id="designation" class="mt-1 block w-full" required />
            </div>
            {{-- FAMILLE --}}
            <div class="mb-4 flex">
                <div class="mb-4 w-full mr-2">
                    <x-input-label for="famille_id"> Famille</x-input-label>
                    <select name="famille_id" id="famille_id" class="select" required>
                        <!-- Options should be populated dynamically -->
                    </select>
                </div>
                <div class="mb-4 w-full">
                    <x-input-label for="sous_famille_id">Sous Famille</x-input-label>
                    <div class="flex">
                        <select name="sous_famille_id" id="sous_famille_id" class="select-left w-full" required>
                            <!-- Options should be populated dynamically -->
                        </select>
                        <a href="{{ route('matieres.create_sous_famille') }}" target="_blank"
                            class="btn-select-right"><x-icons.add /></a>
                    </div>
                </div>
            </div>
            {{-- STANDARD --}}
            <div class="mb-4 flex">
                <div class="mb-4 w-full mr-2">
                    <x-input-label for="dossier_standard_id">Dossier Standard</x-input-label>
                    <select name="dossier_standard_id" id="dossier_standard_id" class="select">
                        <!-- Options should be populated dynamically -->
                    </select>
                </div>
                <div class="mb-4 w-full">
                    <x-input-label for="standard_version_id">Standard
                        Version</x-input-label>
                    <select name="standard_version_id" id="standard_version_id" class="select">
                        <!-- Options should be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="mb-4 flex">
                <div class=" w-full mr-2">
                    <x-input-label for="quantite" value="{{ __('Quantité') }}" />
                    <x-text-input type="number" name="quantite" id="quantite" class="mt-1 block w-full"
                        value="0" />
                </div>
                <div class="w-1/4">
                    <x-input-label for="unite_id">Unité</x-input-label>
                    <select name="unite_id" id="unite_id" class="mt-1 pt-4 select" required>
                        <!-- Options should be populated dynamically -->
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
                <x-input-label for="stock_min" value="{{ __('Stock Minimum') }}" optionnel />
                <x-text-input type="number" name="stock_min" id="stock_min" class="mt-1 block w-full" value="0" />
            </div>
            <div class="col-span-2 flex justify-between">
                <button type="button" class="btn" x-on:click="$dispatch('close')">Annuler</button>
                <button type="submit" class="btn">Ajouter</button>
            </div>

    </div>
</div>
</form>
<script></script>
</div>
