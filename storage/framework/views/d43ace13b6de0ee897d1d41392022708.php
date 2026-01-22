<?php
    $ommited_keys = [
        'created_at',
        'updated_at',
        'password',
        'remember_token',
        'email_verified_at',
        'id',
        'undeletable',
    ];

    // Fonction pour résoudre automatiquement les informations des IDs
    function resolveIdInfo($key, $value) {
        if ($value === null || !is_numeric($value)) return null;

        try {
            switch($key) {
                case 'matiere_id':
                    $matiere = \App\Models\Matiere::find($value);
                    if (!$matiere) return ['name' => 'Matière #' . $value, 'details' => []];

                    $details = [];
                    $fillableFields = $matiere->getFillable();
                    foreach ($fillableFields as $field) {
                        if (isset($matiere->$field) && $matiere->$field !== null && $matiere->$field !== '') {
                            $details[$field] = $matiere->$field;
                        }
                    }
                    return ['name' => $matiere->name ?? 'Matière #' . $value, 'details' => $details];
                case 'societe_matiere_id':
                    $matiere = \App\Models\SocieteMatiere::find($value);
                    if (!$matiere) return ['name' => 'Matière #' . $value, 'details' => []];

                    $details = [];
                    $fillableFields = $matiere->getFillable();
                    foreach ($fillableFields as $field) {
                        if (isset($matiere->$field) && $matiere->$field !== null && $matiere->$field !== '') {
                            $details[$field] = $matiere->$field;
                        }
                    }
                    return ['name' => $matiere->name ?? 'Matière #' . $value, 'details' => $details];

                case 'societe_id':
                    $societe = \App\Models\Societe::find($value);
                    if (!$societe) return ['name' => 'Société #' . $value, 'details' => []];

                    $details = [];
                    $fillableFields = $societe->getFillable();
                    foreach ($fillableFields as $field) {
                        if (isset($societe->$field) && $societe->$field !== null && $societe->$field !== '') {
                            $details[$field] = $societe->$field;
                        }
                    }
                    return ['name' => $societe->name ?? 'Société #' . $value, 'details' => $details];

                case 'user_id':
                    $user = \App\Models\User::find($value);
                    if (!$user) return ['name' => 'Utilisateur #' . $value, 'details' => []];

                    $details = [];
                    $fillableFields = $user->getFillable();
                    foreach ($fillableFields as $field) {
                        if (isset($user->$field) && $user->$field !== null && $user->$field !== '') {
                            $details[$field] = $user->$field;
                        }
                    }
                    return ['name' => ($user->first_name ?? '') . ' ' . ($user->last_name ?? ''), 'details' => $details];

                case 'role_id':
                    $role = \App\Models\Role::find($value);
                    if (!$role) return ['name' => 'Rôle #' . $value, 'details' => []];

                    $details = [];
                    $fillableFields = $role->getFillable();
                    foreach ($fillableFields as $field) {
                        if (isset($role->$field) && $role->$field !== null && $role->$field !== '') {
                            $details[$field] = $role->$field;
                        }
                    }
                    return ['name' => $role->name ?? 'Rôle #' . $value, 'details' => $details];

                case 'entite_id':
                    $entite = \App\Models\Entite::find($value);
                    if (!$entite) return ['name' => 'Entité #' . $value, 'details' => []];

                    $details = [];
                    $fillableFields = $entite->getFillable();
                    foreach ($fillableFields as $field) {
                        if (isset($entite->$field) && $entite->$field !== null && $entite->$field !== '') {
                            $details[$field] = $entite->$field;
                        }
                    }
                    return ['name' => $entite->name ?? 'Entité #' . $value, 'details' => $details];

                default:
                    // Résolution automatique pour les IDs non spécifiés
                    if (str_ends_with($key, '_id')) {
                        $modelName = str_replace('_id', '', $key);
                        $modelClass = 'App\\Models\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $modelName)));

                        try {
                            if (class_exists($modelClass)) {
                                $model = $modelClass::find($value);
                                if ($model) {
                                    $displayName = $model->name ?? $model->title ?? $model->label ?? $model->designation ?? ucfirst($modelName) . ' #' . $value;
                                    $details = [];

                                    // Récupérer tous les attributs fillable du modèle
                                    $fillableFields = $model->getFillable();
                                    foreach ($fillableFields as $field) {
                                        if (isset($model->$field) && $model->$field !== null && $model->$field !== '') {
                                            $details[$field] = $model->$field;
                                        }
                                    }

                                    return [
                                        'name' => $displayName,
                                        'details' => $details
                                    ];
                                }
                            }
                        } catch (\Exception $e) {
                            // Si la classe n'existe pas ou erreur, on retourne un fallback
                        }
                    }
                    return null;
            }
        } catch (\Exception $e) {
            return ['name' => ucfirst(str_replace('_', ' ', $key)) . ' #' . $value, 'details' => []];
        }
    }

    // Fonction pour formater une valeur avec tooltip si nécessaire
    function formatValueWithTooltip($key, $value, $originalValue = null) {
        if ($value === null) return ['display' => 'null', 'tooltip' => [], 'hasTooltip' => false];

        // Utiliser la valeur originale si elle existe pour résoudre les IDs
        $valueToResolve = $originalValue !== null ? $originalValue : $value;

        if (is_numeric($valueToResolve)) {
            $info = resolveIdInfo($key, $valueToResolve);
            if ($info && !empty($info['details'])) {
                return [
                    'display' => $info['name'],
                    'tooltip' => $info['details'],
                    'hasTooltip' => true
                ];
            }
        }

        return [
            'display' => $value,
            'tooltip' => [],
            'hasTooltip' => false
        ];
    }
?>

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
    <?php $__env->startSection('title', 'Historique'); ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="<?php echo e(route('administration.index')); ?>"
                class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm"><?php echo __('Administration'); ?></a>
                >> <?php echo e(__('Historique')); ?>

            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center ">

                <form method="GET" action="<?php echo route('model_changes.index'); ?>"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                    <input type="text" name="search" placeholder="Rechercher..." value="<?php echo request('search'); ?>"
                        oninput="debounceSubmit(this.form)"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">

                    <div class="flex items-center my-1">
                        <label for="start_date"
                            class="mx-4 text-gray-900 dark:text-gray-100"><?php echo __('Après le '); ?></label>
                        <input type="date" name="start_date" onblur="updateDateInputs(this)" id="start_date"
                            value="<?php echo old('start_date', request('start_date')); ?>"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="end_date"
                            class="mr-2 text-gray-900 dark:text-gray-100"><?php echo __('Avant le'); ?></label>
                        <input type="date" name="end_date" onblur="updateDateInputs(this)" id="end_date"
                            value="<?php echo old('end_date', request('end_date')); ?>"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100"><?php echo __('Quantité'); ?></label>
                        <input type="number" name="nombre" id="nombre" value="<?php echo old('nombre', request('nombre', 50)); ?>"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="ml-2 btn sm:mt-0 md:mt-0 lg:mt-0">
                        <?php echo __('Rechercher'); ?>

                    </button>
                </form>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Utilisateur
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        type de modèle
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Changement
                                    </th>

                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Type de changement
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="<?php echo request()->get('show_deleted') ? 'bg-gray-100 dark:bg-gray-900' : 'bg-white dark:bg-gray-800'; ?> divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__currentLoopData = $modelChanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $before = $change->before;
                                        $after = $change->after;
                                        $event = $change->event;

                                        // Sauvegarder les valeurs originales pour la résolution des IDs
                                        $originalBefore = $before;
                                        $originalAfter = $after;

                                        if (
                                            isset($after['first_name']) &&
                                            $event === 'updating' &&
                                            $before['deleted_at'] !== null &&
                                            $after['deleted_at'] == null
                                        ) {
                                            $event = 'restauré le ' . $change->created_at->format('d/m/Y à H:i');
                                            $before = 'custom';
                                            $after =
                                                'Compte ' .
                                                $after['first_name'] .
                                                ' ' .
                                                $after['last_name'] .
                                                ' restauré';
                                        }
                                        if (
                                            isset($after['name']) &&
                                            $event === 'updating' &&
                                            $before['deleted_at'] !== null &&
                                            $after['deleted_at'] == null
                                        ) {
                                            $event = 'restauré le ' . $change->created_at->format('d/m/Y à H:i');
                                            $before = 'custom';
                                            $after = $after['name'];
                                        }

                                        if (isset($before['role_id'])) {
                                            $before['role_id'] =
                                                \App\Models\Role::find($before['role_id'])->name ?? 'Unknown';
                                        }
                                        if (isset($after['role_id'])) {
                                            $after['role_id'] =
                                                \App\Models\Role::find($after['role_id'])->name ?? 'Unknown';
                                        }
                                        if (isset($before['entite_id'])) {
                                            $before['entite_id'] =
                                                \App\Models\Entite::find($before['entite_id'])->name ?? 'Unknown';
                                        }
                                        if (isset($after['entite_id'])) {
                                            $after['entite_id'] =
                                                \App\Models\Entite::find($after['entite_id'])->name ?? 'Unknown';
                                        }
                                        if (isset($before['user_id'])) {
                                            $user = \App\Models\User::find($before['user_id']);
                                            $before['user_id'] = $user ? ($user->first_name . ' ' . $user->last_name) : 'Unknown';
                                        }
                                        if (isset($after['user_id'])) {
                                            $user = \App\Models\User::find($after['user_id']);
                                            $after['user_id'] = $user ? ($user->first_name . ' ' . $user->last_name) : 'Unknown';
                                        }
                                        if ($event === 'creating') {
                                            $before = ' ';
                                            $event = 'Créé le ' . $change->created_at->format('d/m/Y à H:i') . '.';
                                        }
                                        if ($event === 'updating') {
                                            $event = 'Modifié le ' . $change->created_at->format('d/m/Y à H:i') . '.';
                                        }
                                        if ($event === 'deleting') {
                                            $before = ' ';
                                            unset($after['deleted_at']);
                                            $event = 'Supprimé le ' . $change->created_at->format('d/m/Y à H:i') . '.';
                                        }

                                        $change->event = $event;
                                        $change->before = $before;
                                        $change->after = $after;
                                    ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                    <?php echo e(substr($change->user->first_name ?? 'N', 0, 1)); ?><?php echo e(substr($change->user->last_name ?? 'A', 0, 1)); ?>

                                                </div>
                                                <div>
                                                    <div class="font-medium"><?php echo $change->user->first_name ?? 'N/A'; ?> <?php echo $change->user->last_name ?? ''; ?></div>
                                                    <?php if($change->user->role ?? false): ?>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo $change->user->role->name; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($change->model_type === 'SocieteMatiere'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                <?php elseif($change->model_type === 'MouvementStock'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                <?php elseif($change->model_type === 'User'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                <?php endif; ?>">
                                                <?php echo $change->model_type; ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            <div class="space-y-1">
                                                <?php if($change->before == 'custom'): ?>
                                                    <div class="bg-green-50 dark:bg-green-900/20 p-2 rounded-md border-l-4 border-green-400">
                                                        <?php echo $change->after; ?>

                                                    </div>
                                                <?php elseif(is_array($change->before) && is_array($change->after)): ?>
                                                    <?php $__currentLoopData = $change->before; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(($change->after[$key] ?? null) != $value && !in_array($key, $ommited_keys)): ?>
                                                            <?php
                                                                $originalBeforeValue = is_array($originalBefore) && isset($originalBefore[$key]) ? $originalBefore[$key] : null;
                                                                $originalAfterValue = is_array($originalAfter) && isset($originalAfter[$key]) ? $originalAfter[$key] : null;

                                                                $beforeFormatted = formatValueWithTooltip($key, $value, $originalBeforeValue);
                                                                $afterFormatted = formatValueWithTooltip($key, $change->after[$key] ?? null, $originalAfterValue);
                                                            ?>
                                                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded-md border-l-4 border-yellow-400">
                                                                <div class="flex items-center justify-between">
                                                                    <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?> :</span>
                                                                </div>
                                                                <div class="flex items-center mt-1 space-x-2">
                                                                    <?php if($beforeFormatted['hasTooltip']): ?>
                                                                        <?php if (isset($component)) { $__componentOriginal7875b222dc4d64f17fd6d2e345da8799 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tooltip','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tooltip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                                             <?php $__env->slot('slot_item', null, []); ?> 
                                                                                <span class="bg-red-100 dark:bg-red-900/50 px-2 py-1 rounded text-xs cursor-help border border-red-200 dark:border-red-700">
                                                                                    <?php echo $beforeFormatted['display']; ?>

                                                                                </span>
                                                                             <?php $__env->endSlot(); ?>
                                                                             <?php $__env->slot('slot_tooltip', null, []); ?> 
                                                                                <div class="space-y-1">
                                                                                    <?php $__currentLoopData = $beforeFormatted['tooltip']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailKey => $detailValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <div><strong><?php echo e($detailKey); ?>:</strong> <?php echo e($detailValue); ?></div>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>
                                                                             <?php $__env->endSlot(); ?>
                                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $attributes = $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $component = $__componentOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
                                                                    <?php else: ?>
                                                                        <span class="bg-red-100 dark:bg-red-900/50 px-2 py-1 rounded text-xs">
                                                                            <?php echo $beforeFormatted['display']; ?>

                                                                        </span>
                                                                    <?php endif; ?>

                                                                    <?php if (isset($component)) { $__componentOriginald88937ee957874c050ccbc67a5e19575 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald88937ee957874c050ccbc67a5e19575 = $attributes; } ?>
<?php $component = App\View\Components\Icon::resolve(['size' => '1','type' => 'arrow_forward','class' => 'icons-no_hover text-gray-400'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Icon::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald88937ee957874c050ccbc67a5e19575)): ?>
<?php $attributes = $__attributesOriginald88937ee957874c050ccbc67a5e19575; ?>
<?php unset($__attributesOriginald88937ee957874c050ccbc67a5e19575); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald88937ee957874c050ccbc67a5e19575)): ?>
<?php $component = $__componentOriginald88937ee957874c050ccbc67a5e19575; ?>
<?php unset($__componentOriginald88937ee957874c050ccbc67a5e19575); ?>
<?php endif; ?>

                                                                    <?php if($afterFormatted['hasTooltip']): ?>
                                                                        <?php if (isset($component)) { $__componentOriginal7875b222dc4d64f17fd6d2e345da8799 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tooltip','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tooltip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                                             <?php $__env->slot('slot_item', null, []); ?> 
                                                                                <span class="bg-green-100 dark:bg-green-900/50 px-2 py-1 rounded text-xs cursor-help border border-green-200 dark:border-green-700">
                                                                                    <?php echo $afterFormatted['display']; ?>

                                                                                </span>
                                                                             <?php $__env->endSlot(); ?>
                                                                             <?php $__env->slot('slot_tooltip', null, []); ?> 
                                                                                <div class="space-y-1">
                                                                                    <?php $__currentLoopData = $afterFormatted['tooltip']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailKey => $detailValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <div><strong><?php echo e($detailKey); ?>:</strong> <?php echo e($detailValue); ?></div>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>
                                                                             <?php $__env->endSlot(); ?>
                                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $attributes = $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $component = $__componentOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
                                                                    <?php else: ?>
                                                                        <span class="bg-green-100 dark:bg-green-900/50 px-2 py-1 rounded text-xs">
                                                                            <?php echo $afterFormatted['display']; ?>

                                                                        </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php elseif(is_array($change->before) && !is_array($change->after)): ?>
                                                    <div class="bg-red-50 dark:bg-red-900/20 p-2 rounded-md border-l-4 border-red-400">
                                                        <?php $__currentLoopData = $change->before; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if(!in_array($key, $ommited_keys)): ?>
                                                                <?php
                                                                    $originalBeforeValue = is_array($originalBefore) && isset($originalBefore[$key]) ? $originalBefore[$key] : null;
                                                                    $beforeFormatted = formatValueWithTooltip($key, $value, $originalBeforeValue);
                                                                ?>
                                                                <div class="flex items-center space-x-2">
                                                                    <span class="font-medium"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?>:</span>
                                                                    <?php if($beforeFormatted['hasTooltip']): ?>
                                                                        <?php if (isset($component)) { $__componentOriginal7875b222dc4d64f17fd6d2e345da8799 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tooltip','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tooltip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                                             <?php $__env->slot('slot_item', null, []); ?> 
                                                                                <span class="cursor-help underline decoration-dotted">
                                                                                    <?php echo $beforeFormatted['display']; ?>

                                                                                </span>
                                                                             <?php $__env->endSlot(); ?>
                                                                             <?php $__env->slot('slot_tooltip', null, []); ?> 
                                                                                <div class="space-y-1">
                                                                                    <?php $__currentLoopData = $beforeFormatted['tooltip']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailKey => $detailValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <div><strong><?php echo e($detailKey); ?>:</strong> <?php echo e($detailValue); ?></div>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>
                                                                             <?php $__env->endSlot(); ?>
                                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $attributes = $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $component = $__componentOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
                                                                    <?php else: ?>
                                                                        <span><?php echo $beforeFormatted['display']; ?></span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php elseif(!is_array($change->before) && is_array($change->after)): ?>
                                                    <div class="bg-green-50 dark:bg-green-900/20 p-2 rounded-md border-l-4 border-green-400">
                                                        <?php $__currentLoopData = $change->after; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if(!in_array($key, $ommited_keys)): ?>
                                                                <?php
                                                                    $originalAfterValue = is_array($originalAfter) && isset($originalAfter[$key]) ? $originalAfter[$key] : null;
                                                                    $afterFormatted = formatValueWithTooltip($key, $value, $originalAfterValue);
                                                                ?>
                                                                <div class="flex items-center space-x-2">
                                                                    <span class="font-medium"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?>:</span>
                                                                    <?php if($afterFormatted['hasTooltip']): ?>
                                                                        <?php if (isset($component)) { $__componentOriginal7875b222dc4d64f17fd6d2e345da8799 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tooltip','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tooltip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                                                             <?php $__env->slot('slot_item', null, []); ?> 
                                                                                <span class="cursor-help underline decoration-dotted">
                                                                                    <?php echo $afterFormatted['display']; ?>

                                                                                </span>
                                                                             <?php $__env->endSlot(); ?>
                                                                             <?php $__env->slot('slot_tooltip', null, []); ?> 
                                                                                <div class="space-y-1">
                                                                                    <?php $__currentLoopData = $afterFormatted['tooltip']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailKey => $detailValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <div><strong><?php echo e($detailKey); ?>:</strong> <?php echo e($detailValue); ?></div>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>
                                                                             <?php $__env->endSlot(); ?>
                                                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $attributes = $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $component = $__componentOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
                                                                    <?php else: ?>
                                                                        <?php if(is_array($afterFormatted['display'])): ?>
                                                                            <ul class="list-disc list-inside">
                                                                                <?php $__currentLoopData = $afterFormatted['display']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <li><?php echo $item; ?></li>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            </ul>
                                                                        <?php else: ?>
                                                                            <span><?php echo $afterFormatted['display']; ?></span>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <?php if($change->before === null && $change->after === null): ?>
                                                        <span class="text-gray-400 italic">Aucun changement détecté</span>
                                                    <?php else: ?>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="bg-red-100 dark:bg-red-900/50 px-2 py-1 rounded text-xs">
                                                                <?php echo $change->before === null ? 'N/A' : $change->before; ?>

                                                            </span>
                                                            <?php if (isset($component)) { $__componentOriginald88937ee957874c050ccbc67a5e19575 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald88937ee957874c050ccbc67a5e19575 = $attributes; } ?>
<?php $component = App\View\Components\Icon::resolve(['size' => '1','type' => 'arrow_forward','class' => 'icons-no_hover text-gray-400'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Icon::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald88937ee957874c050ccbc67a5e19575)): ?>
<?php $attributes = $__attributesOriginald88937ee957874c050ccbc67a5e19575; ?>
<?php unset($__attributesOriginald88937ee957874c050ccbc67a5e19575); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald88937ee957874c050ccbc67a5e19575)): ?>
<?php $component = $__componentOriginald88937ee957874c050ccbc67a5e19575; ?>
<?php unset($__componentOriginald88937ee957874c050ccbc67a5e19575); ?>
<?php endif; ?>
                                                            <span class="bg-green-100 dark:bg-green-900/50 px-2 py-1 rounded text-xs">
                                                                <?php echo $change->after === null ? 'N/A' : $change->after; ?>

                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <div class="flex items-center">
                                                <?php if(str_contains($change->event, 'Créé')): ?>
                                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                <?php elseif(str_contains($change->event, 'Modifié')): ?>
                                                    <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                                <?php elseif(str_contains($change->event, 'Supprimé')): ?>
                                                    <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                <?php elseif(str_contains($change->event, 'restauré')): ?>
                                                    <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                                                <?php endif; ?>
                                                <?php echo $change->event; ?>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-center items-center pb-3">
                        <div>
                            <?php echo e($modelChanges->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
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
<?php /**PATH /home/vagrant/code/montaza/resources/views/model_changes/index.blade.php ENDPATH**/ ?>