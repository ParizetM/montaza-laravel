<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-700">
        <th scope="col"></th>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach ($notifications as $notification)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">

                    @php

                        $data = json_decode($notification->data, true);
                    @endphp
                    <div>
                        <div class="notification-content">
                            @if (isset($specifyType))
                                @if ($notification->type == 'system')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        Système
                                    </span>
                                @endif
                            @endif
                            <h3 class="text-lg font-semibold text-wrap">{{ $data['title'] ?? 'N/A' }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-wrap">
                                {{ $data['message'] ?? 'N/A' }}
                            </p>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <strong>Action:</strong> {{ $data['action'] ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <form action="{{ route('notifications.lu', $notification->id ) }}" method="post">
                        @csrf
                        @method('PATCH')
                    <div class="flex items-center">
                        <button type="submit" class="btn-select-right">
                            <x-icon type="send" size="1" class=" icons-no_hover" />
                        </button>
                    </div>
                    </form>
                </td>
        @endforeach
    </tbody>

</table>
