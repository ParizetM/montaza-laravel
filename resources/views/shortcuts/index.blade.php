<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Raccourcis') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('Éditez vos raccourcis') }}
        </p>
    </x-slot>

    <div class="py-12">
        <div class="grid grid-cols-1 sm:grid-cols-6 gap-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="col-span-5 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ">
                    {{ __('Raccourcis') }}</h2>
                <div class="">
                    <form method="POST" action="{{ route('shortcuts.store') }}">
                        @csrf
                        <ul id="sortable" class="sm:pr-7">
                            @foreach ($shortcuts as $shortcut)
                                <li class="flex items-center py-2 " data-id="{{ $shortcut->id }}">
                                    <span class="ml-2 "><x-icons.re-order size="2"
                                            class="icons-no_hover mr-2" /></span>
                                    <div
                                        class=" flex justify-between w-full border-b border-gray-800 dark:border-gray-200 p-2">
                                        <div class="flex items-center ">
                                            @php $iconComponent = 'icons.' . $shortcut->icon; @endphp
                                            <div id="icon-{{ $shortcut->id }}">
                                                <div
                                                    class="mt-4 sm:mt-0 inline-flex items-center px-2 py-2 bg-gray-100 dark:bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-wides focus:ring-indigo-200 disabled:opacity-25 transition">
                                                    <x-dynamic-component :component="$iconComponent" size="2"
                                                        class="icons-no_hover " />
                                                </div>
                                            </div>
                                            <p class="ml-2 text-gray-800 dark:text-gray-200">
                                                {{ $shortcut->title }}
                                            </p>
                                        </div>
                                        <div class="flex items-center">


                                            {{ $shortcut->is_added ? 'checked' : '' }}
                                            <label for="is_added-{{ $shortcut->id }}"
                                                class="inline-flex items-center space-x-4 cursor-pointer dark:text-gray-100 text-gray-800 mr-4 mb-1">
                                                <span class="relative">
                                                    <input id="is_added-{{ $shortcut->id }}"
                                                        name="is_added-{{ $shortcut->id }}" type="checkbox"
                                                        class="hidden peer" onchange=""
                                                        {{ $shortcut->isAdded() ? 'checked' : '' }} />
                                                    <div
                                                        class="w-10 h-6 rounded-full shadow-inner bg-gray-400 dark:bg-gray-600 peer-checked:bg-violet-400 peer-checked:dark:bg-violet-600">
                                                    </div>
                                                    <div
                                                        class="absolute inset-y-0 left-0 w-4 h-4 m-1 rounded-full shadow peer-checked:right-0 peer-checked:left-auto bg-gray-800 dark:bg-gray-100">
                                                    </div>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <button type="submit" class="btn mt-4 float-right">
                            {{ __('Enregistrer') }}
                        </button>
                    </form>
                </div>
            </div>

            <div
                class="col-span-2 w-48 p-4 pt-3 bg-white dark:bg-gray-700 shadow rounded-lg h-fit relative sm:fixed sm:top-auto sm:right-8 mx-auto sm:mr-10
            ">
                <div class="">
                    <div class="flex justify-between mb-4">
                        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight ">
                            {{ __('raccourcis') }}</h2>
                        <p class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                            title="Modifier les raccourcis">
                            <x-icons.apps-edit :size="1" class="icons-no_hover" />
                        </p>
                    </div>
                    <div id="shortcuts-resultat">
                        @include('shortcuts.partials.shortcuts')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateShortcuts() {
            const selectedShortcuts = [];
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkedBox => {
            const iconId = checkedBox.id.replace('is_added-', 'icon-');
            const iconElement = document.getElementById(iconId);
            if (iconElement) {
                selectedShortcuts.push(iconElement.innerHTML);
            }
            });
            const shortcutsResultat = document.getElementById('shortcuts-resultat');
            shortcutsResultat.innerHTML = selectedShortcuts.join('');
            shortcutsResultat.classList.add('grid', 'grid-cols-3', 'gap-4');
        }

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateShortcuts);
        });

        // Execute once at the beginning
        updateShortcuts();
    </script>
</x-app-layout>
