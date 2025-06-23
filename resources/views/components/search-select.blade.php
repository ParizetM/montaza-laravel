{{-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\components\search-select.blade.php --}}
@props([
    'options' => [],
    'placeholder' => 'Sélectionner une option...',
    'searchPlaceholder' => 'Rechercher...',
    'name' => 'search_select',
    'value' => '',
    'required' => false,
    'id' => uniqid('search_select_'),
    'onChange' => null,
    'class' => '', // Ajout de l'attribut class
])

<div x-data="{
    open: false,
    search: '',
    selected: '{{ $value }}',
    selectedText: '',
    options: {{ json_encode($options) }},
    get filteredOptions() {
        if (this.search === '') return this.options;
        return this.options.filter(option =>
            option.text.toLowerCase().includes(this.search.toLowerCase())
        );
    },
    selectOption(value, text, disabled = false) {
        if (disabled) return; // Empêcher la sélection si l'option est désactivée

        this.selected = value;
        this.selectedText = text;
        this.open = false;
        this.search = '';

        // Déclencher le callback onChange si fourni
        @if($onChange)
        {{ $onChange }};
        @endif
    },
    toggleOpen() {
        this.open = !this.open;
        if (this.open) {
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
                this.positionDropdown();
            });
        }
    },
    closeDropdown() {
        this.open = false;
    },
    positionDropdown() {
        if (!this.open) return;

        const button = this.$refs.selectButton;
        const dropdown = this.$refs.dropdown;

        // Positionnement du dropdown par rapport au bouton
        const rect = button.getBoundingClientRect();
        const dropdownWidth = rect.width;
        const dropdownLeft = Math.max(10, Math.min(rect.left, window.innerWidth - dropdownWidth - 10)); // 10px du bord
        const dropdownTop = rect.bottom + window.scrollY;

        dropdown.style.width = `${dropdownWidth}px`;
        dropdown.style.left = `${dropdownLeft}px`;

        // Ajuster la position verticale et la hauteur si le dropdown dépasse en bas
        const dropdownHeight = dropdown.offsetHeight;
        const viewportHeight = window.innerHeight;
        if (dropdownTop + dropdownHeight > viewportHeight) {
            const maxHeight = viewportHeight - rect.bottom - 20; // 20px de marge
            dropdown.style.maxHeight = `${Math.max(100, maxHeight)}px`; // Minimum 100px
            dropdown.style.top = `${dropdownTop}px`;
        } else {
            dropdown.style.maxHeight = 'calc(100vh - 100px)'; // Réinitialiser si nécessaire
            dropdown.style.top = `${dropdownTop}px`;
        }
    }
}" x-init="$nextTick(() => { $watch('open', value => { if(value) positionDropdown() }) })" @resize.window="if(open) positionDropdown()" @click.away="closeDropdown()" class="relative w-full {{ $class }}"> <!-- Ajout de {{ $class }} -->

    <!-- Champ caché pour le formulaire -->
    <input type="hidden" :name="name" :value="selected" {{ $required ? 'required' : '' }}
        {{ $id ? "id=$id" : '' }}>

    <!-- Bouton principal du select -->
    <button type="button" @click="toggleOpen()" class="select w-full text-left flex items-center justify-between" x-ref="selectButton">
        <span x-text="selectedText || '{{ $placeholder }}'"
            :class="{ 'text-gray-500 dark:text-gray-400': !selectedText, 'text-gray-900 dark:text-gray-100': selectedText }"></span>
        <span class="ml-2 flex-shrink-0">
            <svg class="h-4 w-4 icons-no_hover" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </span>
    </button>

    <!-- Menu déroulant placé en dehors du conteneur parent -->
    <template x-teleport="body">
        <div x-show="open" x-transition x-ref="dropdown"
            class="fixed z-1000 bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg overflow-auto"
            style="max-height: calc(100vh - 100px);">

            <!-- Champ de recherche -->
            <div class="sticky top-0 bg-gray-100 dark:bg-gray-900 p-2 border-b border-gray-300 dark:border-gray-700 z-20">
                <input type="text" x-model="search" x-ref="searchInput" {{ $id ? "id=$id" . '-searchInput' : '' }}
                    placeholder="{{ $searchPlaceholder }}"
                    class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @click.stop>
            </div>

            <!-- Options -->
            <div class="py-1">
                <template x-for="option in filteredOptions" :key="option.value">
                    <div @click="selectOption(option.value, option.text, option.disabled)"
                        :class="{
                            'bg-blue-500 dark:bg-blue-600 text-white': selected == option.value && !option.disabled,
                            'text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700': selected != option
                                .value && !option.disabled,
                            'text-gray-400 dark:text-gray-500 cursor-not-allowed': option.disabled,
                            'cursor-pointer': !option.disabled
                        }"
                        class="select-none relative px-3 py-2 text-sm transition-colors duration-150">
                        <span x-text="option.text" class="block truncate"></span>
                        <span x-show="selected == option.value && !option.disabled"
                            class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </template>

                <!-- Message si aucun résultat -->
                <div x-show="filteredOptions.length === 0"
                    class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                    Aucun résultat trouvé
                </div>
            </div>
        </div>
    </template>
</div>
