@foreach ($notifications as $notification)
    <tr id="{{ 'notification-'.$notification->id }}">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
            @php
                $data = json_decode($notification->data, true);
            @endphp
            <div>
                <div class="notification-content">
                    @if (isset($specifyType) && $specifyType)
                        @if ($notification->type == 'system')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                Système
                            </span>
                        @endif
                    @endif
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">

                        {{ $notification->created_at->format('d/m/Y H:i') }}
                    </span>
                    <small class="text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>

                    <h3 class="text-lg font-semibold text-wrap">{{ $data['title'] ?? 'N/A' }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-wrap">
                        {{ $data['message'] ?? 'N/A' }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $data['action'] ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

            <div class="flex items-center flex-col">
                <button type="button" class="btn-select-top-right">
                    <x-icon type="open_in_new" size="1" class=" icons-no_hover" />
                </button>
                <button type="button" class="btn-select-square" title="transférer">
                    <x-icon type="send" size="1" class=" icons-no_hover" />
                </button>
                @if (!$notification->read)
                    <button type="button" class="btn-select-bottom-right" onclick="marquerCommeLu({{ $notification->id }})" title="Marquer comme Lu">
                        <x-icon type="read" size="1" class="icons-no_hover" />
                    </button>
                    @else
                    <button type="button" class="btn-select-bottom-right" onclick="marquerCommeNonLu({{ $notification->id }})" title="Marquer comme non-lu">
                        <x-icon type="unread" size="1" class="icons-no_hover" />
                    </button>
                @endif
            </div>
        </td>
    </tr>
@endforeach
