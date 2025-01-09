<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp_cde.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Demandes de prix et commandes</a>
                >>
                {!! __('Créer une demande de prix') !!}
            </h2>
        </div>
    </x-slot>
    <div class="py-4">
        <div class="max-w-8xl mx-auto sm:px-4 lg:px-6">
            <div class="shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-100 px-2 grid grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <div class="flex gap-2">
                        <select name="famille" id="famille_id_search"
                            class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                            <option value="" selected>{!! __('Tous les types&nbsp;&nbsp;') !!}</option>
                            @foreach ($familles as $famille)
                                <option value="{{ $famille->id }}"
                                    {{ request('famille') == $famille->id ? 'selected' : '' }}>
                                    {!! $famille->nom . '&nbsp;&nbsp;' !!}
                                </option>
                            @endforeach
                        </select>
                        <select name="sous_famille" id="sous_famille_id_search"
                            class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                            <option value="" selected>{!! __('Toutes les sous-familles &nbsp;&nbsp;') !!}</option>
                        </select>
                        <x-text-input placeholder="Recherchez une matière" id="searchbar" class="w-full" />

                    </div>
                    <div class="min-h-96 overflow-hidden bg-gray-100 dark:bg-gray-900 rounded">
                        <table>
                            <thead>
                                <th colspan="100">
                                    matières
                                </th>
                            </thead>
                            <tbody id="matiere-table">
                                <tr>
                                    <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                                        Recherchez une matière
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <x-text-input label="Nom" name="nom" placeholder="Nom de la demande de prix" />
                    <div class="min-h-96 overflow-hidden bg-gray-100 dark:bg-gray-900 rounded">
                        <table>
                            <thead>
                                <th>Ref</th>
                                <th>Désignation</th>
                                <th>Quantité</th>
                                <th>Actions</th>
                            </thead>
                            <tbody id="matiere-choisi-table">
                                <tr id="no-matiere">
                                    <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                                        Aucune matière sélectionnée
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateSousFamilles() {
            var familleId = document.getElementById('famille_id_search').value;
            var sousFamilleSelect = document.getElementById('sous_famille_id_search');

            // Efface les anciennes options
            sousFamilleSelect.innerHTML =
                '<option value="" selected>Toutes les sous-familles &nbsp;&nbsp;</option>';

            if (familleId) {
                fetch(`/matieres/famille/${familleId}/sous-familles/json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(sousFamille => {
                            var option = document.createElement('option');
                            option.value = sousFamille.id;
                            option.textContent = sousFamille.nom;
                            sousFamilleSelect.appendChild(option);
                            var sousFamilleId = new URLSearchParams(window.location.search).get('sous_famille');

                            if (sousFamilleId) {
                                document.getElementById('sous_famille_id_search').value = sousFamilleId;
                                console.log(sousFamilleId);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des sous familles :', error);
                    });
            }
        }
        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('famille_id_search').addEventListener('change', function() {
                updateSousFamilles();
            });
            const familleId = document.getElementById('famille_id_search').value;
            const sousFamilleId = document.getElementById('sous_famille_id_search').value;
            const searchbar = document.getElementById('searchbar');
            const matiereTable = document.getElementById('matiere-table');
            searchbar.addEventListener('input', async (e) => {
                const search = e.target.value;
                const response = await fetch(
                    `/matieres/quickSearch?search=${encodeURIComponent(search)}&famille=${familleId}&sous_famille=${sousFamilleId}`
                );
                const data = await response.json();
                matiereTable.innerHTML = '';
                data.matieres.forEach(matiere => {
                    const tr = document.createElement('tr');
                    tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                        'rounded-r-md', 'overflow-hidden','bg-white', 'dark:bg-gray-800');
                    tr.innerHTML = `
            <td class="text-left px-4">${matiere.refInterne || '-'}</td>
            <td class="text-left px-4">${matiere.designation || '-'}</td>
            <td class="text-right "><button class="btn-select-square float-right" data-matiere-id="${matiere.id}"
                    data-matiere-ref="${matiere.refInterne}" data-matiere-designation="${matiere.designation}"
                    onclick="addMatiere(event)">+</button></td>

                `;
                    matiereTable.appendChild(tr);
                });
            });
        });
        function addMatiere(event) {
            const matiereId = event.target.getAttribute('data-matiere-id');
            const matiereRef = event.target.getAttribute('data-matiere-ref');
            const matiereDesignation = event.target.getAttribute('data-matiere-designation');
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const tr = document.createElement('tr');
            tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                'rounded-r-md', 'overflow-hidden','bg-white', 'dark:bg-gray-800');
            tr.innerHTML = `
            <td class="text-left px-4">${matiereRef || '-'}</td>
            <td class="text-left px-4">${matiereDesignation || '-'}</td>
            <td class="text-right px-4 flex items-center">
                <button type="button" class="btn-decrement px-2" onclick="decrementQuantity(this)">-</button>
                <x-text-input type="number" name="quantite[${matiereId}]" class="w-20 text-right mx-2" value="1" min="1" />
                <button type="button" class="btn-increment px-2" onclick="incrementQuantity(this)">+</button>
            </td>
            <td class="text-right px-4">
                <button class="btn-select-square float-right" data-matiere-id="${matiereId}" onclick="removeMatiere(event)">-</button>
            </td>
        `;
            if (matiereChoisiTable.querySelector('#no-matiere')) {
                matiereChoisiTable.innerHTML = '';
            }
        matiereChoisiTable.appendChild(tr);
    }
    function removeMatiere(event) {
        const matiereId = event.target.getAttribute('data-matiere-id');
        const row = event.target.closest('tr');
        row.remove();
        const matiereChoisiTable = document.getElementById('matiere-choisi-table');
        if (matiereChoisiTable.querySelectorAll('tr').length === 0) {
            const tr = document.createElement('tr');
            tr.id = 'no-matiere';
            tr.innerHTML = `
                <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                    Aucune matière sélectionnée
                </td>
            `;
            matiereChoisiTable.appendChild(tr);
        }
    }
    function incrementQuantity(button) {
        const input = button.previousElementSibling;
        input.value = parseInt(input.value) + 1;
    }
    function decrementQuantity(button) {
        const input = button.nextElementSibling;
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
    </script>
</x-app-layout>
