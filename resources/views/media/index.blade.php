<!-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\media\index.blade.php -->
<x-app-layout>
    @section('title', 'Gestion des Pièces jointes')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestion des Pièces jointes') }}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center">
                <form method="GET" action="{{ route('media.index') }}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center gap-2">

                    <x-select-custom name="type" id="type" class="" :selected="request('type')"
                        onchange="this.form.submit()">
                        <x-opt value="" selected>{{ __('Tous les types') }}</x-opt>
                        @foreach ($media_types as $media_type)
                            <x-opt value="{{ $media_type->id }}">
                                <div
                                    class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center
                                    {{ $media_type->background_color_light ? 'bg-[' . $media_type->background_color_light . ']' : 'bg-gray-100' }}
                                    {{ $media_type->text_color_light ? 'text-[' . $media_type->text_color_light . ']' : 'text-gray-800' }}
                                    {{ $media_type->background_color_dark ? 'dark:bg-[' . $media_type->background_color_dark . ']' : 'dark:bg-gray-700' }}
                                    {{ $media_type->text_color_dark ? 'dark:text-[' . $media_type->text_color_dark . ']' : 'dark:text-gray-200' }}">
                                    {{ $media_type->nom }}
                                </div>
                            </x-opt>
                        @endforeach
                    </x-select-custom>
                    <input type="text" name="search" placeholder="{{ __('Rechercher...') }}"
                        value="{{ request('search') }}"
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-center ml-4 my-1">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100">{{ __('Quantité') }}</label>
                        <input type="number" name="nombre" id="nombre"
                            value="{{ old('nombre', request('nombre', 50)) }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2">
                    </div>
                    <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                        {{ __('Rechercher') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Table des médias -->
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('Aperçu') }}</th>
                                    <th>{{ __('Nom du fichier') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Taille') }}</th>
                                    <th>{{ __('Association') }}</th>
                                    <th>{{ __('Ajouté par') }}</th>
                                    <th>{{ __('Date d\'ajout') }}</th>
                                    <th class="text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($medias as $media)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <!-- Aperçu du média -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex-shrink-0 h-16 w-16">
                                                @if (str_contains($media->mime_type ?? '', 'image'))
                                                    <a href="{{ route('media.show', $media->id) }}" target="_blank"
                                                        class="block w-16 h-16">
                                                        <img src="{{ route('media.show', $media->id) }}"
                                                            alt="{{ $media->original_filename ?? $media->filename }}"
                                                            class="w-full h-full object-cover object-center rounded"
                                                            style="max-width:64px;max-height:64px;">
                                                    </a>
                                                @elseif(str_contains($media->mime_type ?? '', 'pdf'))
                                                    <a href="{{ route('media.show', $media->id) }}" target="_blank"
                                                        class=" bg-gray-100 dark:bg-gray-800 h-32 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                                        <div class="text-center">
                                                            <svg class="w-16 h-16 text-red-500 mx-auto" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                                                                Cliquez
                                                                pour ouvrir</p>
                                                        </div>
                                                    </a>
                                                @else
                                                    <a href="{{ route('media.show', $media->id) }}" target="_blank"
                                                        class="block bg-gray-100 dark:bg-gray-800 h-32 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                                        <div class="text-center">
                                                            <svg class="w-16 h-16 text-gray-500 mx-auto" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                            <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                                                                Cliquez
                                                                pour ouvrir</p>
                                                        </div>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Nom du fichier -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $media->original_filename ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $media->filename ?? 'N/A' }}
                                            </div>
                                            <div class="max-h-42 overflow-y-auto">
                                                @include('media.commentaire', ['media' => $media])
                                            </div>
                                        </td>
                                        <!-- Type de média -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <div
                                                class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center
                                    {{ $media->mediaType?->background_color_light ? 'bg-[' . $media->mediaType->background_color_light . ']' : 'bg-gray-100' }}
                                    {{ $media->mediaType?->text_color_light ? 'text-[' . $media->mediaType->text_color_light . ']' : 'text-gray-800' }}
                                    {{ $media->mediaType?->background_color_dark ? 'dark:bg-[' . $media->mediaType->background_color_dark . ']' : 'dark:bg-gray-700' }}
                                    {{ $media->mediaType?->text_color_dark ? 'dark:text-[' . $media->mediaType->text_color_dark . ']' : 'dark:text-gray-200' }}">
                                                {{ $media->mediaType->nom ?? 'N/A' }}
                                            </div>
                                        </td>

                                        <!-- Taille du fichier -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $media->size ? formatNumberBytes($media->size) : 'N/A' }}

                                        </td>
                                        <!-- Association -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if ($media->mediaable_type == 'App\Models\Cde' && $media->mediaable)
                                                <a href="{{ route('cde.show', $media->mediaable->id) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors border border-blue-200 dark:border-blue-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    {{ $media->mediaable->code ?? 'Commande' }}
                                                </a>
                                            @elseif ($media->mediaable_type == 'App\Models\Ddp' && $media->mediaable)
                                                <a href="{{ route('ddp.show', $media->mediaable->id) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 hover:bg-emerald-200 dark:hover:bg-emerald-800 transition-colors border border-emerald-200 dark:border-emerald-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    {{ $media->mediaable->code ?? 'Demande de prix' }}
                                                </a>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ __('Aucune association') }}
                                                </span>
                                            @endif
                                        </td>


                                        <!-- Ajouté par -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $media->user?->first_name ?? 'N/A' }} {{ $media->user?->last_name ?? '' }}
                                        </td>
                                        <!-- Date d'ajout -->
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $media->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-end space-x-2">

                                                <a href="#"
                                                   onclick="openEditModal({{ $media->id }}, '{{ addslashes($media->original_filename) }}', {{ $media->media_type_id ?? 'null' }})"
                                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                   title="Modifier">
                                                    <x-icons.edit size="1.2" />
                                                </a>
                                                <x-boutons.supprimer modalTitle="Supprimer la pièce jointe"
                                                    confirmButtonText="Confirmer la suppression"
                                                    cancelButtonText="Annuler"
                                                    formAction="{{ route('media.destroy', $media->id) }}"
                                                    modalName="delete-media-modal-{{ $media->id }}"
                                                    errorName="delete-media-{{ $media->id }}"
                                                    userInfo="Êtes-vous sûr de vouloir supprimer cette pièce jointe ? Cette action est irréversible.">
                                                    <x-slot:customButton>
                                                        <button type="button"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                            title="Supprimer">
                                                            <x-icons.delete size="1.2" />
                                                        </button>
                                                    </x-slot:customButton>
                                                </x-boutons.supprimer>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('Aucun pièce jointe trouvé.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (isset($medias) && method_exists($medias, 'links'))
                        <div class="mt-6">
                            {{ $medias->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'édition -->
    <x-modal name="edit-media" maxWidth="md">
        <div class="p-4">
            <a x-on:click="$dispatch('close')">
                <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
            </a>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Modifier la pièce jointe') }}
            </h2>

            <form id="edit-media-form" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-input-label for="edit_original_filename" :value="__('Nom du fichier')" />
                    <x-text-input id="edit_original_filename" name="original_filename" type="text"
                                  class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('original_filename')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="edit_media_type_id" :value="__('Type de média')" />
                    <x-select-custom name="media_type_id" id="edit_media_type_id" class="mt-1 block w-full">
                        <x-opt value="">{{ __('Sélectionner un type') }}</x-opt>
                        @foreach ($media_types as $media_type)
                            <x-opt value="{{ $media_type->id }}">
                                <div
                                    class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center
                                    {{ $media_type->background_color_light ? 'bg-[' . $media_type->background_color_light . ']' : 'bg-gray-100' }}
                                    {{ $media_type->text_color_light ? 'text-[' . $media_type->text_color_light . ']' : 'text-gray-800' }}
                                    {{ $media_type->background_color_dark ? 'dark:bg-[' . $media_type->background_color_dark . ']' : 'dark:bg-gray-700' }}
                                    {{ $media_type->text_color_dark ? 'dark:text-[' . $media_type->text_color_dark . ']' : 'dark:text-gray-200' }}">
                                    {{ $media_type->nom }}
                                </div>
                            </x-opt>
                        @endforeach
                    </x-select-custom>
                    <x-input-error :messages="$errors->get('media_type_id')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-media')">
                        {{ __('Annuler') }}
                    </x-secondary-button>
                    <x-primary-button type="submit">
                        {{ __('Mettre à jour') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        function openEditModal(mediaId, originalFilename, mediaTypeId) {
            document.getElementById('edit_original_filename').value = originalFilename;
            document.getElementById('edit_media_type_id').value = mediaTypeId || '';
            document.getElementById('edit-media-form').action = '{{ route("media.update", "") }}/' + mediaId;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-media' }));
        }
    </script>
</x-app-layout>
