<div class="flex items-center lg:flex-col">
    @if (!isset($no_redirect))
        <a type="button" class="btn-select-top-left"
            href="{{ route('notifications.detail', ['id' => $notification->id]) }}">
            <x-icon type="open_in_new" size="1" class=" icons-no_hover" />
        </a>
    @endif
    <button type="button" class="btn-select-square" title="transférer"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'transferno')">
        <x-icon type="send" size="1" class="icons-no_hover" />
    </button>
    @if (!$notification->read)
        <button type="button" class="btn-select-bottom-right" onclick="marquerCommeLu({{ $notification->id }})"
            title="Marquer comme Lu">
            <x-icon type="read" size="1" class="icons-no_hover" />
        </button>
    @else
        @if (!isset($no_redirect))
            <button type="button" class="btn-select-bottom-right" onclick="marquerCommeNonLu({{ $notification->id }})"
                title="Marquer comme Lu">
                <x-icon type="unread" size="1" class="icons-no_hover" />
            </button>
        @else
            <form method="POST" action="{{ route('notifications.nonlu', ['id' => $notification->id]) }}"
                class="inline">
                @csrf
                <button type="submit" class="btn-select-bottom-right" title="Marquer comme non-lu">
                    <x-icon type="unread" size="1" class="icons-no_hover" />
                </button>
            </form>
        @endif
    @endif
    <x-modal name="transfer-notif-modal" />
</div>
