<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['text', 'position' => 'auto']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['text', 'position' => 'auto']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div x-data="tooltip('<?php echo e($position); ?>')"
     x-init="init()"
     class="relative inline-flex items-center justify-center cursor-help ml-1 align-middle text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
     @mouseenter="handleInteraction($event)"
     @mouseleave="hideTooltip()"
     @click="isMobile && handleInteraction($event)"
     role="button"
     tabindex="0"
     @keydown.enter.prevent="handleInteraction($event)"
     @keydown.space.prevent="handleInteraction($event)">

    <!-- The Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" <?php echo e($attributes->merge(['class' => 'w-4 h-4'])); ?>>
      <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
    </svg>

    <!-- The Tooltip Content (Teleported) -->
    <template x-teleport="body">
        <div x-ref="tooltip"
             x-show="show"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             :style="style"
             :class="[tooltipClass, { 'invisible': isPositioning }]"
             class="fixed z-[9999]"
             @mouseenter="enterTooltip()"
             @mouseleave="leaveTooltip()">

            <div class="px-3 py-2 text-xs font-normal text-center text-white bg-gray-900 rounded-lg shadow-xl dark:bg-gray-700 max-w-xs leading-snug break-words">
                <?php echo e($text); ?>

            </div>
        </div>
    </template>
</div>
<?php /**PATH /home/vagrant/code/montaza/resources/views/components/help-icon.blade.php ENDPATH**/ ?>