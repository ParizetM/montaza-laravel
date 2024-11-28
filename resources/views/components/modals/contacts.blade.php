<x-modal name="{{ $name ?? 'contacts-modal' }}" focusable maxWidth="5xl"
>
    <div class="p-8">
        <table class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gradient-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poste</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($contacts as $contact)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $contact->prenom }} {{ $contact->nom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $contact->fonction }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <a href="mailto:{{ $contact->email }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-300 dark:hover:text-blue-500">{{ $contact->email }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            @if ($contact->telephone_fixe)
                                <div><strong>Fixe : </strong><a href="tel:{{ $contact->telephone_fixe }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-300 dark:hover:text-blue-500">{{ $contact->telephone_fixe }}</a></div>
                            @endif
                            @if ($contact->telephone_portable)
                                <div><strong>Portable : </strong><a href="tel:{{ $contact->telephone_portable }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-300 dark:hover:text-blue-500">{{ $contact->telephone_portable }}</a></div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-modal>
