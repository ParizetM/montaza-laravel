<form id="create-affaire-form" method="POST" action="{{ route('affaires.store') }}">

    @csrf
    <div class="mb-4">
        <label for="code" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Code</label>
        <input type="text" name="code" id="code" value="{{ $code ?? '' }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden" required>
    </div>
    <div class="mb-4">
        <label for="nom" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Nom</label>
        <input type="text" name="nom" id="nom" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden" required>
    </div>
    <div class="mb-4">
        <label for="budget" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Budget</label>
        <input type="number" step="0.01" name="budget" id="budget" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden">
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label for="date_debut" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden" required>
        </div>
        <div>
            <label for="date_fin_prevue" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Date de fin prévue</label>
            <input type="date" name="date_fin_prevue" id="date_fin_prevue" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden" required>
        </div>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="btn btn-primary">Créer</button>
    </div>
</form>
