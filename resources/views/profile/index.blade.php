<x-app-layout>
    <x-slot name="header">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Profils') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Liste des profils utilisateur ') }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center">
                <label for="Toggle1"
                    class="inline-flex items-center space-x-4 cursor-pointer dark:text-gray-100 text-gray-800 mr-4 mb-1">
                    <span class="whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">Afficher les comptes
                        supprimé</span>
                    <span class="relative">
                        <input id="Toggle1" type="checkbox" class="hidden peer" onchange="toggleDeletedProfiles(this)"
                            {{ request()->get('show_deleted') ? 'checked' : '' }}>
                        <div
                            class="w-10 h-6 rounded-full shadow-inner bg-gray-400 dark:bg-gray-600 peer-checked:bg-violet-400 peer-checked:dark:bg-violet-600">
                        </div>
                        <div
                            class="absolute inset-y-0 left-0 w-4 h-4 m-1 rounded-full shadow peer-checked:right-0 peer-checked:left-auto bg-gray-800 dark:bg-gray-100">
                        </div>
                    </span>
                </label>

                <form method="GET" action="{{ route('profile.index') }}" class="mr-4 sm:mr-0 sm:flex-grow">
                    <input type="text" name="search" placeholder="Rechercher..."
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit"
                        class="ml-2 mb-1 inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition">
                        {{ __('Rechercher') }}
                    </button>
                </form>
                <a href="{{ route('register') }}" class="btn sm:ml-4">
                    {{ __('Créer un utilisateur') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Téléphone
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        E-mail
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Poste
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="{{ request()->get('show_deleted') ? 'bg-gray-100 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }} divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ preg_replace('/(\d{2})(?=\d)/', '$1 ', $user->phone) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $user->email }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $user->role->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if ($user->deleted_at)
                                                <form method="GET" action="{{ route('profile.restore', $user) }}"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('GET')
                                                    <button type="submit"
                                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600">Restaurer</button>
                                                </form>
                                            @else
                                                <a href="{{ route('profile.edit', ['id' => $user]) }}"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600">Modifier</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDeletedProfiles(checkbox) {
            const url = new URL(window.location.href);
            if (checkbox.checked) {
                url.searchParams.set('show_deleted', '1');
            } else {
                url.searchParams.delete('show_deleted');
            }
            window.location.href = url.toString();
        }
    </script>


</x-app-layout>
