@props([
    'name' => '',
    'id' => null,
    'value' => null,
    'disabled' => false,
    'required' => false,
    'onlyNumber' => false,
    'class' => '',
    'placeholder' => 'Entrez un nombre'
])

@php
    $id ??= $name;
    $isNon = $value === 'non';
@endphp

<div class="flex {{ $class }}">
    <button
        type="button"
        id="{{ $id }}-non-button"
        class="{{ $isNon ? 'bg-indigo-600 text-white font-bold shadow-sm' : ($onlyNumber ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'  ) }} px-4 py-2 rounded-l-md transition-all duration-200 {{ ($disabled || $onlyNumber) ? '' : 'cursor-pointer' }}"
        {{ ($disabled || $onlyNumber) ? 'disabled' : '' }}
        onclick="toggleNon('{{ $id }}', '{{ $name }}', {{ $required ? 'true' : 'false' }})"
    >
        NON
    </button>
    <input
        type="number"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ $value !== 'non' ? $value : '' }}"
        placeholder="{{ $placeholder }}"
        {{ $disabled || $isNon ? 'disabled' : '' }}
        {{ !$isNon && $required ? 'required' : '' }}
        class="block w-full px-4 pb-2 pt-3 border-y border-r border-gray-300 dark:border-gray-600 rounded-r-md font-medium text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 transition-all duration-200 {{ $isNon ? 'bg-gray-100 dark:bg-gray-800 opacity-50' : 'bg-white dark:bg-gray-900' }} focus:border-indigo-600"
    >
</div>

<script class="SCRIPT">
    const toggleNon = (id, name, required) => {
        const input = document.getElementById(id);
        const button = document.getElementById(`${id}-non-button`);
        let hiddenInput = document.getElementById(`${id}-hidden`);
        const nonSelected = input.disabled;

        if (!nonSelected) {
            // Set to NON
            input.value = '';
            input.disabled = true;
            input.required = false;
            input.classList.add('bg-gray-100', 'dark:bg-gray-800', 'opacity-50');
            input.classList.remove('bg-white', 'dark:bg-gray-900');

            button.classList.add('bg-indigo-600', 'text-white', 'font-bold', 'shadow-sm');
            button.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-300', 'dark:hover:bg-gray-600');

            // Set hidden value to 'non'
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = name;
                hiddenInput.value = 'non';
                hiddenInput.id = `${id}-hidden`;
                if (required) hiddenInput.required = true;
                input.parentNode.appendChild(hiddenInput);
            }
        } else {
            // Set to number input
            input.disabled = false;
            if (required) input.required = true;
            input.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'opacity-50');
            input.classList.add('bg-white', 'dark:bg-gray-900');

            button.classList.remove('bg-indigo-600', 'text-white', 'font-bold', 'shadow-sm');
            button.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-300', 'dark:hover:bg-gray-600');

            // Remove hidden input if exists
            if (hiddenInput) {
                hiddenInput.remove();
            }
        }
    };

    // Initialize state on page load
    document.addEventListener('DOMContentLoaded', () => {
        const id = '{{ $id }}';
        const isNon = {{ $isNon ? 'true' : 'false' }};
        const required = {{ $required ? 'true' : 'false' }};
        const input = document.getElementById(id);

        if (isNon) {
            input.disabled = true;
            input.required = false;
            input.classList.add('bg-gray-100', 'dark:bg-gray-800', 'opacity-50');

            // Create hidden input for 'non' value if required
            if (required) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = '{{ $name }}';
                hiddenInput.value = 'non';
                hiddenInput.id = `${id}-hidden`;
                input.parentNode.appendChild(hiddenInput);
            }
        }
    });
</script>
