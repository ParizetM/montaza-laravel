<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['size','class']));

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

foreach (array_filter((['size','class']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<?php if (isset($component)) { $__componentOriginal7a2a48b30c2f7779cf3fc14b70b04994 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a2a48b30c2f7779cf3fc14b70b04994 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.cde','data' => ['size' => $size,'class' => $class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.cde'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($size),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($class)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a2a48b30c2f7779cf3fc14b70b04994)): ?>
<?php $attributes = $__attributesOriginal7a2a48b30c2f7779cf3fc14b70b04994; ?>
<?php unset($__attributesOriginal7a2a48b30c2f7779cf3fc14b70b04994); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a2a48b30c2f7779cf3fc14b70b04994)): ?>
<?php $component = $__componentOriginal7a2a48b30c2f7779cf3fc14b70b04994; ?>
<?php unset($__componentOriginal7a2a48b30c2f7779cf3fc14b70b04994); ?>
<?php endif; ?><?php /**PATH /home/vagrant/code/montaza/storage/framework/views/017bede46981388c6f21fe413d9c87b2.blade.php ENDPATH**/ ?>