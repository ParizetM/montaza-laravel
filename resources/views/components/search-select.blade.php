{{-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\components\search-select.blade.php --}}
@props([
    'options' => [],
    'placeholder' => 'Sélectionner une option...',
    'searchPlaceholder' => 'Rechercher...',
    'name' => 'search_select',
    'value' => '',
    'required' => false,
    'id' => uniqid('search_select_'),
    'onChange' => null
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
            });
        }
    },
    init() {
        // Chercher d'abord une option avec selected: true
        const selectedOption = this.options.find(opt => opt.selected);
        if (selectedOption) {
            this.selected = selectedOption.value;
            this.selectedText = selectedOption.text;
        } else if (this.selected) {
            // Fallback sur la valeur fournie
            const option = this.options.find(opt => opt.value == this.selected);
            if (option) this.selectedText = option.text;
        }
    }
}" class="relative">

    <!-- Champ caché pour le formulaire -->
    <input type="hidden" :name="name" :value="selected" {{ $required ? 'required' : '' }} {{ $id ? "id=$id" : '' }}>

    <!-- Bouton principal du select -->
    <button type="button"
            @click="toggleOpen()"
            class="select w-full text-left flex items-center justify-between">
        <span x-text="selectedText || '{{ $placeholder }}'"
              :class="{'text-gray-500 dark:text-gray-400': !selectedText, 'text-gray-900 dark:text-gray-100': selectedText}"></span>
        <span class="ml-2 flex-shrink-0">
            <svg class="h-4 w-4 icons-no_hover" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </span>
    </button>

    <!-- Menu déroulant -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-10 mt-1 w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg max-h-60 overflow-auto">

        <!-- Champ de recherche -->
        <div class="sticky top-0 bg-gray-100 dark:bg-gray-900 p-2 border-b border-gray-300 dark:border-gray-700 z-20">
            <input type="text"
                   x-model="search"
                   x-ref="searchInput"
                   {{ $id ? "id=$id"."-searchInput" : '' }}
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
                         'text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700': selected != option.value && !option.disabled,
                         'text-gray-400 dark:text-gray-500 cursor-not-allowed': option.disabled,
                         'cursor-pointer': !option.disabled
                     }"
                     class="select-none relative px-3 py-2 text-sm transition-colors duration-150">
                    <span x-text="option.text" class="block truncate"></span>
                    <span x-show="selected == option.value && !option.disabled"
                          class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
            </template>

            <!-- Message si aucun résultat -->
            <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                Aucun résultat trouvé
            </div>
        </div>
    </div>
</div>
