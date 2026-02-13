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
    <?php $__env->startSection('title', 'Administration'); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Administration')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg p-4 flex flex-wrap gap-4">
                <?php if (Auth::check() && Auth::user()->hasPermission('gerer_les_utilisateurs')): ?>
                    <a href="<?php echo e(route('profile.index')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginalfedf51d31852c9ee40b9b6e72adb510a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfedf51d31852c9ee40b9b6e72adb510a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.group','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfedf51d31852c9ee40b9b6e72adb510a)): ?>
<?php $attributes = $__attributesOriginalfedf51d31852c9ee40b9b6e72adb510a; ?>
<?php unset($__attributesOriginalfedf51d31852c9ee40b9b6e72adb510a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfedf51d31852c9ee40b9b6e72adb510a)): ?>
<?php $component = $__componentOriginalfedf51d31852c9ee40b9b6e72adb510a; ?>
<?php unset($__componentOriginalfedf51d31852c9ee40b9b6e72adb510a); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Utilisateurs')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer les utilisateurs')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('gerer_les_utilisateurs')): ?>
                    <a href="<?php echo e(route('personnel.index')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginalfedf51d31852c9ee40b9b6e72adb510a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfedf51d31852c9ee40b9b6e72adb510a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.group','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfedf51d31852c9ee40b9b6e72adb510a)): ?>
<?php $attributes = $__attributesOriginalfedf51d31852c9ee40b9b6e72adb510a; ?>
<?php unset($__attributesOriginalfedf51d31852c9ee40b9b6e72adb510a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfedf51d31852c9ee40b9b6e72adb510a)): ?>
<?php $component = $__componentOriginalfedf51d31852c9ee40b9b6e72adb510a; ?>
<?php unset($__componentOriginalfedf51d31852c9ee40b9b6e72adb510a); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Personnel')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer le personnel de l\'entreprise')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('gerer_les_permissions')): ?>
                    <a href="<?php echo e(route('permissions')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginala3c88cd1fcfa4c93f6b8e63aa21519e3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala3c88cd1fcfa4c93f6b8e63aa21519e3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.key','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.key'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala3c88cd1fcfa4c93f6b8e63aa21519e3)): ?>
<?php $attributes = $__attributesOriginala3c88cd1fcfa4c93f6b8e63aa21519e3; ?>
<?php unset($__attributesOriginala3c88cd1fcfa4c93f6b8e63aa21519e3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala3c88cd1fcfa4c93f6b8e63aa21519e3)): ?>
<?php $component = $__componentOriginala3c88cd1fcfa4c93f6b8e63aa21519e3; ?>
<?php unset($__componentOriginala3c88cd1fcfa4c93f6b8e63aa21519e3); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Permissions et Postes')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer les permissions et les postes')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('voir_historique')): ?>
                    <a href="<?php echo e(route('model_changes.index')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginal24471fc2103b27ac7a927e633ef40e21 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal24471fc2103b27ac7a927e633ef40e21 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.history','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.history'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal24471fc2103b27ac7a927e633ef40e21)): ?>
<?php $attributes = $__attributesOriginal24471fc2103b27ac7a927e633ef40e21; ?>
<?php unset($__attributesOriginal24471fc2103b27ac7a927e633ef40e21); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal24471fc2103b27ac7a927e633ef40e21)): ?>
<?php $component = $__componentOriginal24471fc2103b27ac7a927e633ef40e21; ?>
<?php unset($__componentOriginal24471fc2103b27ac7a927e633ef40e21); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Historique')); ?></h1>
                            <p class="p-1 rounded-sm"><?php echo e(__('Voir l\'historique des modifications')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('gerer_les_donnees_de_reference')): ?>
                    <a href="<?php echo e(route('reference-data.index')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginal87fb472882c6062bc0a5aa12cb16c002 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87fb472882c6062bc0a5aa12cb16c002 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.database','data' => ['class' => 'w-14 h-14 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.database'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-14 h-14 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87fb472882c6062bc0a5aa12cb16c002)): ?>
<?php $attributes = $__attributesOriginal87fb472882c6062bc0a5aa12cb16c002; ?>
<?php unset($__attributesOriginal87fb472882c6062bc0a5aa12cb16c002); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87fb472882c6062bc0a5aa12cb16c002)): ?>
<?php $component = $__componentOriginal87fb472882c6062bc0a5aa12cb16c002; ?>
<?php unset($__componentOriginal87fb472882c6062bc0a5aa12cb16c002); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Données de référence')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer les familles, sous-familles et autres données de base')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('gerer_mail_templates')): ?>
                    <a href="<?php echo e(route('mailtemplates.index')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginal961acbdc9366d1df2f10edb25c6760ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal961acbdc9366d1df2f10edb25c6760ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.inbox-text','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.inbox-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal961acbdc9366d1df2f10edb25c6760ec)): ?>
<?php $attributes = $__attributesOriginal961acbdc9366d1df2f10edb25c6760ec; ?>
<?php unset($__attributesOriginal961acbdc9366d1df2f10edb25c6760ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal961acbdc9366d1df2f10edb25c6760ec)): ?>
<?php $component = $__componentOriginal961acbdc9366d1df2f10edb25c6760ec; ?>
<?php unset($__componentOriginal961acbdc9366d1df2f10edb25c6760ec); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Modèles de mail')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer les modèles de mail et la signature')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('voir_les_ddp_et_cde')): ?>
                    <a href="<?php echo e(route('administration.cdeNote.index',1)); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginale35b182a10cafd90d9ed4f8fc7efef0f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale35b182a10cafd90d9ed4f8fc7efef0f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.edit-note','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.edit-note'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale35b182a10cafd90d9ed4f8fc7efef0f)): ?>
<?php $attributes = $__attributesOriginale35b182a10cafd90d9ed4f8fc7efef0f; ?>
<?php unset($__attributesOriginale35b182a10cafd90d9ed4f8fc7efef0f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale35b182a10cafd90d9ed4f8fc7efef0f)): ?>
<?php $component = $__componentOriginale35b182a10cafd90d9ed4f8fc7efef0f; ?>
<?php unset($__componentOriginale35b182a10cafd90d9ed4f8fc7efef0f); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Notes de commande')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer les notes de commande')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('gerer_info_entreprise')): ?>
                    <a href="<?php echo e(route('administration.info')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginal13845f6a36a199794ba3a9ba32f671f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal13845f6a36a199794ba3a9ba32f671f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.entreprise','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.entreprise'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal13845f6a36a199794ba3a9ba32f671f6)): ?>
<?php $attributes = $__attributesOriginal13845f6a36a199794ba3a9ba32f671f6; ?>
<?php unset($__attributesOriginal13845f6a36a199794ba3a9ba32f671f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13845f6a36a199794ba3a9ba32f671f6)): ?>
<?php $component = $__componentOriginal13845f6a36a199794ba3a9ba32f671f6; ?>
<?php unset($__componentOriginal13845f6a36a199794ba3a9ba32f671f6); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('informations entreprise')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer les informations des entreprises')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('gerer_les_medias')): ?>
                    <a href="<?php echo e(route('media.index')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginal84f00af9a5bd4f4d5327b11c1cd3318d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84f00af9a5bd4f4d5327b11c1cd3318d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.attachement','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.attachement'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84f00af9a5bd4f4d5327b11c1cd3318d)): ?>
<?php $attributes = $__attributesOriginal84f00af9a5bd4f4d5327b11c1cd3318d; ?>
<?php unset($__attributesOriginal84f00af9a5bd4f4d5327b11c1cd3318d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84f00af9a5bd4f4d5327b11c1cd3318d)): ?>
<?php $component = $__componentOriginal84f00af9a5bd4f4d5327b11c1cd3318d; ?>
<?php unset($__componentOriginal84f00af9a5bd4f4d5327b11c1cd3318d); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Pièces jointes')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer les pièces jointes')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasPermission('gerer_l_application')): ?>
                    <a href="<?php echo e(route('administration.appsettings.index')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <?php if (isset($component)) { $__componentOriginal675d5ec13ccf645c64542fb04e9f331e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal675d5ec13ccf645c64542fb04e9f331e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.settings','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.settings'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal675d5ec13ccf645c64542fb04e9f331e)): ?>
<?php $attributes = $__attributesOriginal675d5ec13ccf645c64542fb04e9f331e; ?>
<?php unset($__attributesOriginal675d5ec13ccf645c64542fb04e9f331e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal675d5ec13ccf645c64542fb04e9f331e)): ?>
<?php $component = $__componentOriginal675d5ec13ccf645c64542fb04e9f331e; ?>
<?php unset($__componentOriginal675d5ec13ccf645c64542fb04e9f331e); ?>
<?php endif; ?>
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Paramètres de l\'application')); ?></h1>
                            <p class=" p-1 rounded-sm"><?php echo e(__('Gérer les paramètres principaux de l\'application')); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('administration.icons')); ?>" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                    <?php if (isset($component)) { $__componentOriginal675d5ec13ccf645c64542fb04e9f331e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal675d5ec13ccf645c64542fb04e9f331e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.settings','data' => ['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.settings'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal675d5ec13ccf645c64542fb04e9f331e)): ?>
<?php $attributes = $__attributesOriginal675d5ec13ccf645c64542fb04e9f331e; ?>
<?php unset($__attributesOriginal675d5ec13ccf645c64542fb04e9f331e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal675d5ec13ccf645c64542fb04e9f331e)): ?>
<?php $component = $__componentOriginal675d5ec13ccf645c64542fb04e9f331e; ?>
<?php unset($__componentOriginal675d5ec13ccf645c64542fb04e9f331e); ?>
<?php endif; ?>
                    <div class=" flex flex-col justify-between">
                        <h1 class="text-3xl font-bold mb-6 text-left"><?php echo e(__('Icons')); ?></h1>
                        <p class=" p-1 rounded-sm"><?php echo e(__('Voir tout les icons utilisé')); ?></p>
                    </div>
                </a>
            </div>
        </div>
    </div>
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
<?php /**PATH /home/vagrant/code/montaza/resources/views/administration/index.blade.php ENDPATH**/ ?>