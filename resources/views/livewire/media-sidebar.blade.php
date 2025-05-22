<div>
    <div class="fixed top-1/2 left-0 transform -translate-y-1/2" x-data>
        <button @click="$dispatch('open-volet', 'media-manager')"
            class="btn-select-right flex items-center px-2 py-8 bg-gray-200 dark:bg-gray-800 shadow-lg hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700">
            <x-icon :size="1" type="arrow_back" />
            <span class=" whitespace-nowrap font-medium transform -rotate-90 inline-block w-1  -mb-12">Médias</span>
        </button>

    </div>
    {{-- <div class="media-sidebar p-4 bg-white dark:bg-gray-800 shadow rounded-lg">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex justify-between items-center">
            <span>Documents associés</span>
            <button
                type="button"
                @click="$dispatch('open-volet', 'media-manager')"
                class="px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition-colors">
                Gérer les documents
            </button>
        </h3>

        <!-- Liste simplifiée des documents récents -->
        <div class="mt-3">
            <ul class="space-y-2">
                @if(count($mediaList ?? []) > 0)
                    @foreach($mediaList as $media)
                        <li class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg flex justify-between items-center">
                            <!-- Icône basée sur le type de fichier -->
                            <div class="flex items-center">
                                @if(str_contains($media->mime_type ?? '', 'image'))
                                    <div class="w-8 h-8 mr-2 bg-gray-200 rounded overflow-hidden">
                                        <img src="{{ route('media.show', $media->id) }}" alt="{{ $media->original_filename ?? $media->filename }}" class="w-full h-full object-cover">
                                    </div>
                                @elseif(str_contains($media->mime_type ?? '', 'pdf'))
                                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @endif
                                <span class="text-sm truncate" title="{{ $media->original_filename ?? $media->filename }}">
                                    {{ Str::limit($media->original_filename ?? $media->filename, 20) }}
                                </span>
                            </div>
                            <a href="{{ route('media.show', $media->id) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                        </li>
                    @endforeach
                @else
                    <li class="text-center text-gray-500 dark:text-gray-400 py-4">
                        Aucun document associé
                    </li>
                @endif
            </ul>
        </div>
    </div> --}}

    <!-- Volet modal pour la gestion complète des médias -->
    <x-volet-modal name="media-manager" maxWidth="3xl" position="left">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Gestion des documents</h2>
                <button @click="$dispatch('close-volet', 'media-manager')" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Onglets -->
            <div x-data="{ tab: 'files' }" class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-4">
                        <button @click="tab = 'files'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': tab === 'files', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'files' }" class="pb-3 px-1 border-b-2 font-medium text-sm">
                            Fichiers
                        </button>
                        <button @click="tab = 'upload'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': tab === 'upload', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'upload' }" class="pb-3 px-1 border-b-2 font-medium text-sm">
                            Ajouter des fichiers
                        </button>
                        <button @click="tab = 'qrcode'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': tab === 'qrcode', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'qrcode' }" class="pb-3 px-1 border-b-2 font-medium text-sm">
                            QR Code
                        </button>
                    </nav>
                </div>

                <!-- Contenu des onglets -->
                <div x-show="tab === 'files'" class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if(count($mediaList ?? []) > 0)
                            @foreach($mediaList as $media)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700">
                                        <div class="flex items-center space-x-2">
                                            @if(str_contains($media->mime_type ?? '', 'image'))
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @elseif(str_contains($media->mime_type ?? '', 'pdf'))
                                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            @endif
                                            <span class="font-medium truncate" title="{{ $media->original_filename ?? $media->filename }}">
                                                {{ Str::limit($media->original_filename ?? $media->filename, 25) }}
                                            </span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('media.download', $media->id) }}" target="_blank" class="text-blue-500 hover:text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                            </a>
                                            <button wire:click="deleteMedia({{ $media->id ?? 0 }})" class="text-red-500 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Aperçu du fichier -->
                                    <div class="p-3">
                                        @if(str_contains($media->mime_type ?? '', 'image'))
                                            <a href="{{ route('media.show', $media->id) }}" target="_blank" class="block">
                                                <img src="{{ route('media.show', $media->id) }}" alt="{{ $media->original_filename ?? $media->filename }}" class="w-full h-32 object-cover object-center">
                                            </a>
                                        @elseif(str_contains($media->mime_type ?? '', 'pdf'))
                                            <a href="{{ route('media.show', $media->id) }}" target="_blank" class="block bg-gray-100 dark:bg-gray-800 h-32 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                                <div class="text-center">
                                                    <svg class="w-16 h-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">Cliquez pour ouvrir</p>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ route('media.show', $media->id) }}" target="_blank" class="block bg-gray-100 dark:bg-gray-800 h-32 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                                <div class="text-center">
                                                    <svg class="w-16 h-16 text-gray-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">Cliquez pour ouvrir</p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                    <!-- Informations sur le fichier -->
                                    <div class="px-3 pb-3">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <p>Ajouté par: {{ $media->user->name ?? 'Système' }}</p>
                                            <p>Date: {{ $media->created_at ? $media->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                            <p>Taille: {{ $media->size ? number_format($media->size / 1024, 2) . ' KB' : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-2 text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">Aucun document associé à cette commande</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Formulaire d'upload -->
                <div x-show="tab === 'upload'" class="mt-4">
                    <div class="border-dashed border-2 border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">Glissez-déposez vos fichiers ici ou cliquez pour sélectionner</p>

                        <!-- Zone d'upload avec gestion des événements Livewire -->
                        <div
                            x-data="{
                                uploading: false,
                                progress: 0,
                                handleDrop(e) {
                                    e.preventDefault();
                                    const files = e.dataTransfer.files;
                                    if (files.length) {
                                        @this.uploadMultiple('files', files);
                                    }
                                }
                            }"
                            x-on:dragover.prevent="$el.classList.add('bg-gray-100', 'dark:bg-gray-700')"
                            x-on:dragleave.prevent="$el.classList.remove('bg-gray-100', 'dark:bg-gray-700')"
                            x-on:drop="handleDrop"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            class="mt-4 cursor-pointer transition-colors duration-200 ease-in-out pb-4"
                        >
                            <label class="block">
                                <span class="sr-only">Choisir des fichiers</span>
                                <input type="file" wire:model="files" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600" multiple />
                            </label>

                            <!-- Barre de progression -->
                            <div x-show="uploading" class="mt-4">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" x-bind:style="`width: ${progress}%`"></div>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="`Téléchargement en cours... ${progress}%`"></p>
                            </div>
                        </div>

                        <!-- Message d'erreur -->
                        @error('files.*')
                            <div class="mt-2 text-red-500 text-sm">{{ $message }}</div>
                        @enderror

                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <p>Formats acceptés: JPG, PNG, PDF</p>
                            <p>Taille maximale: 10MB par fichier</p>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div x-show="tab === 'qrcode'" class="mt-4">
                    <div class="text-center">
                        @if($qrUrl)
                            <div class="bg-white p-4 rounded-lg inline-block mb-4">
                                {!! QrCode::size(200)->generate($qrUrl) !!}
                            </div>
                            <p class="mb-4 text-gray-600 dark:text-gray-400">Scannez ce code QR pour télécharger des documents depuis un autre appareil</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Le lien expire dans <span x-data="{ timeLeft: 3600 }" x-init="setInterval(() => timeLeft > 0 ? timeLeft-- : clearInterval(this), 1000)">
                                    <span x-text="`${Math.floor(timeLeft / 60)}m ${timeLeft % 60}s`"></span>
                                </span>
                            </p>
                        @else
                            <p class="mb-4 text-gray-600 dark:text-gray-400">Générez un code QR pour télécharger des documents depuis un autre appareil</p>
                            <button wire:click="generateQrCode" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                Générer un QR Code
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-volet-modal>
</div>
