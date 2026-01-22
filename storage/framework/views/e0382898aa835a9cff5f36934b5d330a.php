<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'size' => 1,
    'class' => 'icons-no_hover',
]));

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

foreach (array_filter(([
    'size' => 1,
    'class' => 'icons-no_hover',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<svg xmlns="http://www.w3.org/2000/svg" height="<?php echo e($size); ?>rem" viewBox="0 0 20 20" width="<?php echo e($size); ?>rem" fill="#e8eaed" class="<?php echo e($class); ?>">
<path d="M6.75 3.5c-1.24 0-2.25 1-2.25 2.25v7.5a.75.75 0 0 1-1.5 0v-7.5a3.75 3.75 0 0 1 7.5-.25v10.25a2.25 2.25 0 0 1-4.5 0V5.77a.75.75 0 0 1 1.5 0v9.98a.75.75 0 0 0 1.5 0v-10C9 4.51 8 3.5 6.75 3.5ZM12 8.25c0-.41.34-.75.75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Zm.75-3.75a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5ZM12 11.25c0-.41.34-.75.75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h2a.75.75 0 0 0 0-1.5h-2Z"></path></svg>
<?php /**PATH /home/vagrant/code/montaza/resources/views/components/icons/attachement.blade.php ENDPATH**/ ?>