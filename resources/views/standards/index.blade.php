<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}"
                    class="hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">Matière</a>
                >>
                {!! __('Standards') !!}
            </h2>
            <button class="ml-auto btn" x-data x-on:click.prevent="$dispatch('open-modal','add-standard')">
                {{ __('Ajouter un standard') }}
            </button>
            <button class="ml-auto btn" x-data x-on:click.prevent="$dispatch('open-modal','add-dossier')">
                {{ __('Ajouter un dossier') }}
            </button>

            <x-modal name="add-standard" focusable :show="count($errors) > 0">
                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <a x-on:click="$dispatch('close')">
                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                </a>
                <form method="post" action="{{ route('standards.store') }}" class="p-4 flex flex-col gap-4"
                    enctype="multipart/form-data">
                    @csrf
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        Ajouter un standard
                    </h1>
                    <x-input-label for="dossier" value="Dossier" class="w-1/4" />
                    <div class="flex w-full">
                        <select name="dossier" id="dossier" class="select-left w-full" required>
                            @foreach ($folders as $folder)
                                <option value="{{ $folder->id }}"
                                    {{ old('dossier') == $folder->id ? 'selected' : '' }}>
                                    {{ $folder->nom }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn-select-right" type="button"
                            x-on:click.prevent="$dispatch('open-modal','add-dossier')">
                            +
                        </button>
                    </div>
                    @error('dossier')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <x-input-label for="file" value="Fichier" class="w-1/4" />
                    <input type="file" name="file" id="file" accept=".pdf" required class="input-file" />
                    @error('file')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <x-input-label for="version" value="Version" class="w-1/4" />
                    <select name="version" id="version" class="select-left" required>
                        @foreach (range('A', 'Z') as $letter)
                            <option value="{{ $letter }}" {{ old('version') == $letter ? 'selected' : '' }}>
                                {{ $letter }}
                            </option>
                        @endforeach
                    </select>
                    @error('version')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click.prevent="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <button class="ms-3 btn" type="submit">
                            {{ __('Ajouter') }}
                        </button>
                    </div>
                </form>
                <script>
                    document.getElementById('dossier').addEventListener('change', function() {
                        checkFileAndRemoveVersions();
                    });

                    document.getElementById('file').addEventListener('change', function() {
                        checkFileAndRemoveVersions();
                    });

                    function checkFileAndRemoveVersions() {
                        const dossierId = document.getElementById('dossier').value;
                        const fileInput = document.getElementById('file');
                        const file = fileInput.files[0];

                        if (dossierId && file) {
                            fetch(`/matieres/standards/${dossierId}/${file.name}/versions/json`)
                                .then(response => response.json())
                                .then(data => {
                                    const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ'];
                                    const availableVersions = alphabet.filter(letter => !data.includes(letter));

                                    const versionSelect = document.getElementById('version');
                                    while (versionSelect.firstChild) {
                                        versionSelect.removeChild(versionSelect.firstChild);
                                    }
                                    availableVersions.forEach(version => {
                                        const option = document.createElement('option');
                                        option.value = version;
                                        option.textContent = version;
                                        versionSelect.appendChild(option);
                                    });
                                });
                        }
                    }
                </script>
            </x-modal>
            <x-modal name="add-dossier" focusable :show="count($errors) > 0">
                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <a x-on:click="$dispatch('close')">
                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                </a>
                <form method="post" action="{{ route('standards.store_dossier') }}" class="p-4 flex flex-col gap-4">
                    @csrf
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        Ajouter un dossier
                    </h1>
                    <x-input-label for="nom" value="Nom du dossier" class="w-1/4" />
                    <x-text-input type="text" name="nom" id="nom" value="{{ old('nom') }}" required class="input" />
                    @error('nom')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click.prevent="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <button class="ms-3 btn" type="submit">
                            {{ __('Ajouter') }}
                        </button>
                    </div>
                </form>
            </x-modal>
        </div>


    </x-slot>

    <div class="py-8 text-gray-800 dark:text-gray-200 ">
        <div class="container mx-auto bg-white dark:bg-gray-800 p-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Standards') !!}
            </h2>
            <ul class=" pl-5 ">
                @foreach ($folders as $folder)
                    <li x-data="{ open: false }" class="group">
                        <div @click="open = !open"
                            class="cursor-pointer flex items-center hover:bg-gray-100 hover:dark:bg-gray-700 p-1 rounded">

                            <x-icons.folder show="!open" class="w-6 h-6" />
                            <x-icons.open-folder show="open" class="w-6 h-6" />
                            <strong class="text-lg ml-1"> {{ $folder->nom }}</strong>
                            <button class="ml-auto"
                                x-on:click.prevent="$dispatch('open-modal','delete-dossier-{{ $folder->id }}')">
                                <x-icons.close class="w-8 h-8 dark:group-hover:fill-gray-200 dark:fill-gray-800" />
                            </button>
                            <x-modal name="delete-dossier-{{ $folder->id }}" focusable>
                                <a x-on:click="$dispatch('close')">
                                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                                </a>
                                <form method="post" action="{{ route('standards.destroy_dossier') }}" class="p-4">
                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden" name="id" value="{{ $folder->id }}" />
                                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        Supression</h1>
                                    <h2 class="text-base font-normal text-gray-900 dark:text-gray-100">
                                        Êtes-vous sûr de vouloir supprimer <strong
                                            class="underline">{{ $folder->nom }}</strong> définitivement ?
                                    </h2>

                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click.prevent="$dispatch('close')">
                                            {{ __('Cancel') }}
                                        </x-secondary-button>

                                        <x-danger-button class="ms-3" type="submit">
                                            {{ __('Delete') }}
                                        </x-danger-button>
                                    </div>
                                </form>
                            </x-modal>
                        </div>
                        <ul x-show="open"
                            class="list-inside pl-5 mt-2 transition-all duration-300 ease-in-out overflow-hidden">
                            @foreach ($folder->standards as $standard)
                                @foreach ($standard->versions as $version)
                                    <li
                                        class="text-gray-700 dark:text-gray-300 pl-8 flex border-l hover:bg-gray-100 hover:dark:bg-gray-700 rounded-r group">
                                        <x-icons.pdf class="w-6 h-6" /><a href="{{ $version->path() }}" class="lien"
                                            target="_blank">
                                            {{ $version->standard->nom }} - {{ $version->version }}
                                        </a>
                                        <button class="ml-auto"
                                            x-on:click.prevent="$dispatch('open-modal','delete-standard-{{ $version->id }}')">

                                            <x-icons.close
                                                class="w-6 h-6 dark:group-hover:fill-gray-200 dark:fill-gray-800 mr-2" />
                                        </button>
                                    </li>
                                    <x-modal name="delete-standard-{{ $version->id }}" focusable>
                                        <a x-on:click="$dispatch('close')">
                                            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                                        </a>
                                        <form method="post" action="{{ route('standards.destroy') }}"
                                            class="p-4">
                                            @csrf
                                            @method('DELETE')

                                            <input type="hidden" name="id" value="{{ $version->id }}" />
                                            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                                Supression</h1>
                                            <h2 class="text-base font-normal text-gray-900 dark:text-gray-100">
                                                Êtes-vous sûr de vouloir supprimer <strong
                                                    class="underline">{{ $version->standard->nom }} -
                                                    {{ $version->version }}</strong> définitivement ?
                                            </h2>

                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click.prevent="$dispatch('close')">
                                                    {{ __('Cancel') }}
                                                </x-secondary-button>

                                                <x-danger-button class="ms-3" type="submit">
                                                    {{ __('Delete') }}
                                                </x-danger-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                @endforeach
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</x-app-layout>
