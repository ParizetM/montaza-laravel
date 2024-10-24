<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-700">
        <th scope="col"></th>
    </thead>
    <tbody @isset($scrollInfini)
    id="notification-list-{{ $tab }}"
    @endisset
        class="divide-y divide-gray-200 dark:divide-gray-700">
        @if ($notifications && $notifications->count() > 0)
            @include('notifications.partials._notifications', $notifications)
        @else
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                    <div class="flex items-center justify-center">
                        <p class="text-gray-500 dark:text-gray-400">Aucune nouvelle notification.</p>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
@isset($scrollInfini)
    <div id="loading-message" style="display: none;">
        <p>Chargement des notifications...</p>
    </div>
@endisset
