<div class="grid grid-cols-3 gap-6 ">
    @foreach ($_shortcuts as $shortcut)
        <a href="{{ route($shortcut->shortcut->url) }}" class="btn mx-auto p-2 hover:bg-gray-50 dark:hover:bg-gray-800" title="{{ $shortcut->shortcut->title }}">
            @php $iconComponent = 'icons.' . $shortcut->shortcut->icon; @endphp
            <x-dynamic-component :component="$iconComponent" size="1.5" class="icons-no_hover" />
        </a>
    @endforeach
</div>
