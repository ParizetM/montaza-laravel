<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Sociétés') !!}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center ">

                <form method="GET" action="{!! route('matieres.index') !!}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                    <select name="famille" id="famille_id_search" class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                        <option value="" selected>{!! __('Tous les types') !!}</option>
                        @foreach ($familles as $famille)
                            <option value="{{ $famille->id }}"
                                {{ request('famille') == $famille->id ? 'selected' : '' }}>
                                {!! $famille->nom . '&nbsp;&nbsp;' !!}
                            </option>
                        @endforeach
                    </select>
                    <select name="sous_famille" id="sous_famille_id_search"
                        class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                        <option value="" selected>{!! __('Toutes les sous-familles') !!}</option>
                    </select>
                    <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}"
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                        <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 50)) !!}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-20 mr-2 ">
                    </div>
                    <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                        {!! __('Rechercher') !!}
                    </button>
                </form>

            </div>
        </div>
    </x-slot>

    <div class="py-8 ">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg shadow-md">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead
                                class="bg-gradient-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800 text-gray-700 dark:text-gray-100">
                                <tr c>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Référence</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Désignation</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Sous-famille</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Standard</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">DN</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Épaisseur</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Unité</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-100">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 flex justify-center items-center pb-3 pagination">
                    <div>

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
                '<option value="" selected>toutes les sous-familles</option>';

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

        document.addEventListener('DOMContentLoaded', function () {
    // Mise à jour des sous-familles au chargement
    updateSousFamilles();

    // Attache les événements pour la recherche dynamique
    document.querySelector('input[name="search"]').addEventListener('input', debounce(liveSearch, 300));
    document.getElementById('famille_id_search').addEventListener('change', function () {
        updateSousFamilles();
        liveSearch();
    });
    document.getElementById('sous_famille_id_search').addEventListener('change', liveSearch);
    document.getElementById('nombre').addEventListener('change', liveSearch);

    // Gestion de la pagination
    document.addEventListener('click', function (event) {
        if (event.target.matches('.pagination a')) {
            event.preventDefault();
            const url = new URL(event.target.href);
            const page = url.searchParams.get('page');
            liveSearch(page);
        }
    });

    // Lancer la première recherche au chargement
    liveSearch();
});

function liveSearch(page = 1) {
    const searchQuery = document.querySelector('input[name="search"]').value.trim();
    const familleId = document.getElementById('famille_id_search').value;
    const sousFamilleId = document.getElementById('sous_famille_id_search').value;
    const nombre = document.getElementById('nombre').value;
    fetch(`/matieres/search?search=${encodeURIComponent(searchQuery)}&famille=${familleId}&sous_famille=${sousFamilleId}&nombre=${nombre}&page=${page}`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur lors de la récupération des données');
            return response.json();
        })
        .then(data => {

            updateTable(data.matieres); // Met à jour le tableau
            updatePagination(data.links); // Met à jour la pagination
        })
        .catch(error => console.error('Erreur lors de la recherche :', error));
}

function updateTable(matieres) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = ''; // Réinitialise le tableau
    matieres.forEach(matiere => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-left py-3 px-4">${matiere.refInterne || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.designation || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.sousFamille || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.standard || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.dn || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.epaisseur || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.Unite || '-'}</td>
        `;
        tbody.appendChild(row);
    });
}

function updatePagination(data) {
    const pagination = document.querySelector('.pagination');
    pagination.innerHTML = data; // Réinitialise la pagination
}


function debounce(func, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };
}

    </script>


</x-app-layout>
