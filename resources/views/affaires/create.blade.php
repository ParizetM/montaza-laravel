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
    <div class="flex justify-end">
        <button type="submit" class="btn btn-primary">Créer</button>
    </div>
</form>
