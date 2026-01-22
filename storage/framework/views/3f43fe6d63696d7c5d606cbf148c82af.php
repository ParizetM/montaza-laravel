<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
    <?php if(Auth::user()->hasPermission('gerer_les_permissions')): ?>

    <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('permissions'),'active' => request()->routeIs('permissions')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('permissions')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('permissions'))]); ?>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
            <?php echo e(__('Permissions')); ?>

        </h2>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
    <?php endif; ?>
    <?php if(Auth::user()->hasPermission('gerer_les_postes')): ?>
        <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('roles'),'active' => request()->routeIs('roles')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('roles')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('roles'))]); ?>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
                <?php echo e(__('Postes')); ?>

            </h2>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
    <?php endif; ?>

    <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center">
        <?php if(Auth::user()->hasPermission('gerer_les_postes')): ?>
        <button type="button" class="btn mb-4" x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'create-role-modal')">
            Créer un Poste
        </button>
    <?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal7ffca04697cbcf805507b53e97c6e571 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7ffca04697cbcf805507b53e97c6e571 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.create_role','data' => ['entites' => $entites]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.create_role'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entites' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entites)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7ffca04697cbcf805507b53e97c6e571)): ?>
<?php $attributes = $__attributesOriginal7ffca04697cbcf805507b53e97c6e571; ?>
<?php unset($__attributesOriginal7ffca04697cbcf805507b53e97c6e571); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7ffca04697cbcf805507b53e97c6e571)): ?>
<?php $component = $__componentOriginal7ffca04697cbcf805507b53e97c6e571; ?>
<?php unset($__componentOriginal7ffca04697cbcf805507b53e97c6e571); ?>
<?php endif; ?>
    </div>
</div>
<?php /**PATH /home/vagrant/code/montaza/resources/views/permissions/navigation.blade.php ENDPATH**/ ?>