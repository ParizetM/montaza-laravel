<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header_nav', null, []); ?> 
        <?php echo $__env->make('permissions.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
     <?php $__env->endSlot(); ?>

    <div class="py-12" id="container">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class=" p-6 text-gray-900 dark:text-gray-100 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        <?php echo e(__('Poste')); ?>

                    </h2>
                    <form action="<?php echo e(route('permissions.edit')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <?php if(isset($role)): ?>
                            <?php if (isset($component)) { $__componentOriginal9250746366bee607630867884391cc2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9250746366bee607630867884391cc2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select_id_role','data' => ['selected' => $role->id,'entites' => $entites,'class' => 'max-w-md select']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select_id_role'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($role->id),'entites' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entites),'class' => 'max-w-md select']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9250746366bee607630867884391cc2f)): ?>
<?php $attributes = $__attributesOriginal9250746366bee607630867884391cc2f; ?>
<?php unset($__attributesOriginal9250746366bee607630867884391cc2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9250746366bee607630867884391cc2f)): ?>
<?php $component = $__componentOriginal9250746366bee607630867884391cc2f; ?>
<?php unset($__componentOriginal9250746366bee607630867884391cc2f); ?>
<?php endif; ?>
                        <?php else: ?>
                            <?php if (isset($component)) { $__componentOriginal9250746366bee607630867884391cc2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9250746366bee607630867884391cc2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select_id_role','data' => ['entites' => $entites,'class' => 'max-w-md select']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select_id_role'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entites' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entites),'class' => 'max-w-md select']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9250746366bee607630867884391cc2f)): ?>
<?php $attributes = $__attributesOriginal9250746366bee607630867884391cc2f; ?>
<?php unset($__attributesOriginal9250746366bee607630867884391cc2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9250746366bee607630867884391cc2f)): ?>
<?php $component = $__componentOriginal9250746366bee607630867884391cc2f; ?>
<?php unset($__componentOriginal9250746366bee607630867884391cc2f); ?>
<?php endif; ?>
                        <?php endif; ?>


                        <div class="mt-6">

                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                <?php echo e(__('Permissions')); ?>

                            </h2>

                            <div class="mb-4">
                                <button type="button" id="toggle-all" class="btn">
                                    Tout cocher
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permission-<?php echo e($permission->id); ?>"
                                            value="<?php echo e($permission->id); ?>" id="permission-<?php echo e($permission->id); ?>"
                                            class="mr-2 permission-checkbox"
                                            <?php
                                                if (isset($role)) {
                                                    foreach ($role->permissions as $role_permission) {
                                                        if ($role_permission->id == $permission->id) {
                                                            echo 'checked';
                                                        }
                                                    }
                                                } ?>>
                                        <label for="permission-<?php echo e($permission->id); ?>"
                                            class="text-gray-900 dark:text-gray-100"><?php echo e(str_replace('_', ' ', $permission->name)); ?>

                                            <small class="text-gray-500 dark:text-gray-400"><br/><?php echo e($permission->description); ?></small>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn">
                                    Mettre à jour les permissions
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        document.querySelector('#role_id').addEventListener('change', function() {
            const roleId = this.value;
            const newUrl = `${window.location.origin}/permissions/${roleId}`;
            window.location.href = newUrl;
            const container = document.getElementById('container');
            const containerHeight = container.offsetHeight;
            container.innerHTML = '<div id="loading-spinner" class="inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50" style="height: ' + containerHeight + 'px;"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style>';

        });

        document.getElementById('toggle-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });

            this.textContent = allChecked ? 'Tout cocher' : 'Tout décocher';
        });

        // Vérifier l'état initial au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const toggleButton = document.getElementById('toggle-all');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            toggleButton.textContent = allChecked ? 'Tout décocher' : 'Tout cocher';
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/vagrant/code/montaza/resources/views/permissions/index.blade.php ENDPATH**/ ?>