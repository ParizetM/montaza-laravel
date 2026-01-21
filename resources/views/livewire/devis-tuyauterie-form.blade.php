<div class="space-y-8">

    <!-- 1. En-tête Affaire -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border-l-4 border-blue-500">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Informations du Chantier
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Référence Projet / Nom Chantier</label>
                <input type="text" wire:model="reference_projet" placeholder="Ex: Remplacement Vapeur Ligne 4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lieu d'intervention (Précis)</label>
                <input type="text" wire:model="lieu_intervention" placeholder="Ex: Usine Nord, Atelier Méca, Niv +2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                <p class="text-xs text-gray-500 mt-1">Influe sur les frais de déplacement et accès.</p>
            </div>

            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Société</label>
                    <select wire:model.live="societe_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white mb-2">
                        <option value="">-- Sélectionner --</option>
                        @foreach($societes as $societe)
                            <option value="{{ $societe->id }}">{{ $societe->raison_sociale }}</option>
                        @endforeach
                    </select>
                    <input type="text" wire:model="client_nom" placeholder="Nom Client (Affiché)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Technique</label>
                    @if($societe_id)
                        <select wire:model.live="societe_contact_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white mb-2">
                            <option value="">-- Sélectionner contact --</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact['id'] }}">{{ $contact['nom'] }}</option>
                            @endforeach
                        </select>
                    @endif
                    <input type="text" wire:model="client_contact" placeholder="Nom Contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse Facturation</label>
                    <textarea wire:model="client_adresse" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date d'émission</label>
                    <input type="date" wire:model="date_emission" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Validité (Jours)</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <input type="number" wire:model="duree_validite" class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white pr-12">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 sm:text-sm">Jours</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Corps du Devis -->
    <div class="space-y-6">
        @foreach($sections as $index => $section)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700 relative">
                <button wire:click="removeSection({{ $index }})" class="absolute top-4 right-4 text-red-500 hover:text-red-700" title="Supprimer la section">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Intitulé de la Zone / Lot</label>
                    <input type="text" wire:model="sections.{{ $index }}.titre" class="mt-1 text-lg font-bold block w-full border-none border-b-2 border-gray-300 focus:border-blue-500 focus:ring-0 dark:bg-gray-800 dark:text-white px-0" placeholder="Ex: Zone 1 - Préfabrication">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-1/3">Désignation</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Matière/Norme</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-20">Qté</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase w-16">Unité</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-24">Prix Achat (Caché)</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-24">P.U. Vente</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-24">Total HT</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($section['lignes'] as $lineIndex => $line)
                                <tr>
                                    <td class="px-2 py-2">
                                        <select wire:model="sections.{{ $index }}.lignes.{{ $lineIndex }}.type" class="block w-full text-xs rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                            <option value="fourniture">Matériel</option>
                                            <option value="main_d_oeuvre">Main d'œuvre</option>
                                            <option value="sous_traitance">Sous-traitance</option>
                                            <option value="consommable">Consommable</option>
                                        </select>
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="text" wire:model.lazy="sections.{{ $index }}.lignes.{{ $lineIndex }}.designation" placeholder="Desc. Technique" class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="text" wire:model.lazy="sections.{{ $index }}.lignes.{{ $lineIndex }}.matiere" placeholder="Ex: 316L, ISO" class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="number" step="0.01" wire:model.live.debounce.500ms="sections.{{ $index }}.lignes.{{ $lineIndex }}.quantite" class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white text-right">
                                    </td>
                                    <td class="px-2 py-2">
                                        <select wire:model="sections.{{ $index }}.lignes.{{ $lineIndex }}.unite" class="block w-full text-xs rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                            <option value="u">U</option>
                                            <option value="ml">ml</option>
                                            <option value="h">h</option>
                                            <option value="f">Forfait</option>
                                            <option value="ens">Ens</option>
                                        </select>
                                    </td>
                                    <td class="px-2 py-2 bg-yellow-50 dark:bg-yellow-900/10">
                                        <input type="number" step="0.01" wire:model.live.debounce.500ms="sections.{{ $index }}.lignes.{{ $lineIndex }}.prix_achat" class="block w-full text-sm border-transparent bg-transparent text-right focus:ring-0 text-gray-500" placeholder="0.00">
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="number" step="0.01" wire:model.live.debounce.500ms="sections.{{ $index }}.lignes.{{ $lineIndex }}.prix_unitaire" class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white text-right font-bold">
                                    </td>
                                    <td class="px-2 py-2 text-right font-mono text-sm">
                                        {{ number_format($line['total_ht'], 2) }} €
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <button wire:click="removeLine({{ $index }}, {{ $lineIndex }})" class="text-gray-400 hover:text-red-500">
                                            &times;
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button wire:click="addLine({{ $index }})" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter une ligne
                    </button>
                </div>
            </div>
        @endforeach

        <button wire:click="addSection" class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-blue-500 hover:text-blue-500 transition-colors flex justify-center items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Ajouter une nouvelle Zone / Lot
        </button>
    </div>

    <!-- 3. Options Métier -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Options & Spécificités Tuyauterie</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Essais -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm uppercase">Essais & CND</h4>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="options.essais_hydrauliques" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Épreuves Hydrauliques</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="options.ressuage" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ressuage (PT)</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="options.radiographie" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Radiographie (RT) - % Soudures</span>
                </label>
            </div>

            <!-- Documents -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm uppercase">Documentation Technique</h4>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="options.dossier_fin_travaux" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Dossier Fin de Travaux (DFT)</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="options.cahier_soudage" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Cahier de Soudage (DMOS/QMOS)</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="options.certificats_matiere" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Certificats Matières (3.1)</span>
                </label>
            </div>

            <!-- Logistique -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm uppercase">Moyens Spécifiques</h4>
                <div class="flex items-center gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="options.nacelle" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Nacelle</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="options.echafaudage" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Échafaudage</span>
                    </label>
                </div>
                <div>
                   <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-2">Forfait Consom. (Gaz, etc.) €</label>
                   <input type="number" wire:model.live.debounce.500ms="options.frais_consommables_forfait" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Pied de Page / Totaux Sticky -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg p-6 h-fit">
             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Conditions Commerciales</h3>
             <div class="grid grid-cols-1 gap-4">
                 <div>
                     <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conditions de Paiement</label>
                     <input type="text" wire:model="conditions_paiement" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                 </div>
                 <div>
                     <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Délai / Planning</label>
                     <input type="text" wire:model="delais_execution" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                 </div>
             </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-700 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-600">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 border-b pb-2">Synthèse Financière</h3>

            <!-- Indicateur Marge (Interne) -->
            <div class="mb-6 p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded border border-yellow-200 dark:border-yellow-700">
                <p class="text-xs text-yellow-800 dark:text-yellow-200 font-bold uppercase">Indicateur Marge (Invisible Client)</p>
                <div class="flex justify-between items-end mt-1">
                    <span class="text-sm text-yellow-700 dark:text-yellow-300">{{ number_format($marge_pourcent, 1) }} %</span>
                    <span class="text-lg font-mono font-bold text-yellow-800 dark:text-yellow-100">{{ number_format($marge_globale, 2) }} €</span>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between text-gray-600 dark:text-gray-300">
                    <span>Total HT</span>
                    <span class="font-mono">{{ number_format($total_ht, 2) }} €</span>
                </div>
                <div class="flex justify-between text-gray-600 dark:text-gray-300 text-sm">
                    <span>TVA (20%)</span>
                    <span class="font-mono">{{ number_format($total_tva, 2) }} €</span>
                </div>
                <div class="pt-4 border-t border-gray-300 dark:border-gray-500 flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Net à payer</span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400 font-mono">{{ number_format($total_ttc, 2) }} €</span>
                </div>
            </div>

            <div class="mt-8">
                <button wire:click="save" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow transition transform hover:scale-105">
                    Enregistrer le Devis
                </button>
            </div>
        </div>
    </div>

</div>
