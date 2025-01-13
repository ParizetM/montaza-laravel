<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                <a href="{{ route('ddp.show', $ddp->id) }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">{!! __('Créer une demande de prix') !!}</a>
                >> Validation
            </h2>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-center">{{ $ddp->nom }}</h1>

            @foreach ($societes as $societe)
                <div class="mb-6">
                    <h2
                        class="text-xl font-semibold text-gray-700 dark:text-gray-200  border-b border-gray-300 dark:border-gray-700 pb-2">
                        {{ $societe->raison_sociale }}
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <ul class="space-y-4 bg-gray-900 py-4 rounded-b-md">
                            @foreach ($ddp->ddpLigne as $ddpLigne)
                                @foreach ($ddpLigne->ddpLigneFournisseur as $ddpLigneFournisseur)
                                    @if ($ddpLigneFournisseur->societe_id == $societe->id)
                                        <li class="ml-4">
                                            <div>
                                                <span>
                                                    {{ $ddpLigne->matiere->ref_interne }}
                                                </span>
                                                <span>
                                                    {{ $ddpLigne->matiere->designation }}
                                                </span>
                                                <span>
                                                    {{ $ddpLigne->quantite }}
                                                </span>
                                                <span title="{{ $ddpLigne->matiere->unite->full }}">
                                                    {{ $ddpLigne->matiere->unite->full }}
                                                </span>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            @endforeach
                        </ul>
                        <div class="flex flex-col p-4 gap-1">
                            <div class="flex flex-col gap-4">
                                <x-input-label value="Établissement" />
                                <select name="etablissement-{{ $societe->id }}" id="etablissement-{{ $societe->id }}"
                                    class="select w-1/2" onchange="changeEtablissement({{ $societe->id }})">

                                    @if ($societe->etablissements->count() == 1)
                                        <option value="{{ $societe->etablissements->first()->id }}" selected>
                                            {{ $societe->etablissements->first()->nom }}
                                        </option>
                                    @else
                                        <option value="">Choisir un établissement</option>
                                        @foreach ($societe->etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}">{{ $etablissement->nom }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="flex flex-col gap-4">
                                <x-input-label value="Destinataire" />
                                <select name="contact-{{ $societe->id }}" id="contact-{{ $societe->id }}"
                                    class="select w-1/2">
                                    <option value="">Choisir un destinataire</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        let societes = JSON.parse(@json($societes));
        function changeEtablissement(societeId) {
            let etablissementId = document.getElementById('etablissement-' + societeId).value;
            let select = document.getElementById('contact-' + societeId);
            select.innerHTML = '';
            let option = document.createElement('option');
            option.value = '';
            option.text = 'Choisir un destinataire';
            select.add(option);
            // societes is already defined outside the function

            societes.forEach(societe => {
                if (societe.id == societeId) {
                    societe.etablissements.forEach(etablissement => {
                        if (etablissement.id == etablissementId) {
                            etablissement.contacts.forEach(contact => {
                                let option = document.createElement('option');
                                option.value = contact.id;
                                option.text = contact.nom + ' ' + contact.prenom;
                                select.add(option);
                            });
                        }
                    });
                }
            });
        }
    </script>


</x-app-layout>
