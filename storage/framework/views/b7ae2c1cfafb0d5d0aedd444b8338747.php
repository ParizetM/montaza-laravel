<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['entites', 'selected', 'user', 'id', 'name', 'class', 'onchange', 'placeholder']));

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

foreach (array_filter((['entites', 'selected', 'user', 'id', 'name', 'class', 'onchange', 'placeholder']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>






<select id="<?php echo e(isset($id) ? $id : 'role_id'); ?>" name="<?php echo e(isset($name) ? $name : 'role_id'); ?>"
    class="block w-full <?php echo e(isset($class) ? $class : 'select'); ?>" required title="Role"
    <?php if(isset($onchange)): ?> onchange="<?php echo e($onchange); ?>" <?php endif; ?>>
    <?php if(isset($placeholder)): ?>
        <option value="" disabled selected><?php echo e($placeholder); ?></option>
    <?php endif; ?>
    <?php $__currentLoopData = $entites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <optgroup label="<?php echo e($entite->name); ?>">
            <?php $__currentLoopData = $entite->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($role->id); ?>"
                    <?php if(isset($selected)): ?> <?php echo e($selected == $role->id ? 'selected' : ''); ?>

                        <?php elseif(isset($user)): ?>
                            <?php echo e(old('role_id') == $role->id ? 'selected' : ($user->role_id == $role->id ? 'selected' : '')); ?>

                        <?php else: ?>
                            <?php echo e(old('role_id') == $role->id ? 'selected' : ''); ?> <?php endif; ?>>
                    <?php echo e($role->name); ?> <?php echo e($role->trashed() ? ' (désactivé)' : ''); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </optgroup>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
<?php /**PATH /home/vagrant/code/montaza/resources/views/components/select_id_role.blade.php ENDPATH**/ ?>