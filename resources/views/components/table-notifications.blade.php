<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-700">
        <th scope="col"></th>
    </thead>
    <tbody
    @isset($scrollInfini)
    id="notification-list-{{ $tab }}"
    @endisset
        class="divide-y divide-gray-200 dark:divide-gray-700">
            @include('notifications.partials._notifications',$notifications)
    </tbody>
</table>
@isset($scrollInfini)

    <div id="loading-message" style="display: none;">
        <p>Chargement des notifications...</p>
    </div>
@endisset
