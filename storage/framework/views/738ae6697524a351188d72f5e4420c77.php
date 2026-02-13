<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value', 'optionnel' => false, 'help' => null]));

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

foreach (array_filter((['value', 'optionnel' => false, 'help' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<label <?php echo e($attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300 flex items-center gap-2'])); ?>>
    <span><?php echo e($value ?? $slot); ?></span>

    <?php if($optionnel): ?>
        <small class="text-gray-500 font-normal">(Optionnel)</small>
    <?php endif; ?>

    <?php if($help): ?>
        <?php if (isset($component)) { $__componentOriginalf80c6e4882377f1e95404ca80788f6ed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf80c6e4882377f1e95404ca80788f6ed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.help-icon','data' => ['text' => $help]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('help-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($help)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf80c6e4882377f1e95404ca80788f6ed)): ?>
<?php $attributes = $__attributesOriginalf80c6e4882377f1e95404ca80788f6ed; ?>
<?php unset($__attributesOriginalf80c6e4882377f1e95404ca80788f6ed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf80c6e4882377f1e95404ca80788f6ed)): ?>
<?php $component = $__componentOriginalf80c6e4882377f1e95404ca80788f6ed; ?>
<?php unset($__componentOriginalf80c6e4882377f1e95404ca80788f6ed); ?>
<?php endif; ?>
    <?php endif; ?>
</label>

<?php /**PATH /home/vagrant/code/montaza/resources/views/components/input-label.blade.php ENDPATH**/ ?>