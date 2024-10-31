<x-modal name="{{ $name ?? 'contacts-modal' }}" focusable>
    <div class="p-8">
        <table class="min-w-full bg-white dark:bg-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Téléphone</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 dark:text-gray-100">
                @foreach ($contacts as $contact)
                    <tr class=" border-b border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-2">{{ $contact->prenom }} {{ $contact->nom }}</td>
                        <td class="px-4 py-2"><a href="mailto:{{ $contact->email }}" class="hover:text-gray-900 dark:hover:text-gray-100">{{ $contact->email }}</a></td>
                        <td class="px-4 py-2">
                            @if ($contact->telephone_fixe)
                                <div><strong>Fixe : </strong><a href="tel:{{ $contact->telephone_fixe }}" class="hover:text-gray-900 dark:hover:text-gray-100">{{ $contact->telephone_fixe }}</a></div>
                            @endif
                            @if ($contact->telephone_portable)
                                <div><strong>Portable : </strong><a href="tel:{{ $contact->telephone_portable }}" class="hover:text-gray-900 dark:hover:text-gray-100">{{ $contact->telephone_portable }}</a></div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-modal>
