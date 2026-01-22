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
    <?php $__env->startSection('title', 'Réparations'); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo __('Réparations du matériels'); ?>

            </h2>
            <div class="flex flex-col sm:flex-row gap-2">
                <?php if(Auth::user()->hasPermission('gerer_les_reparations')): ?>
                    <a href="<?php echo route('reparation.create'); ?>"
                         class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <?php echo __('Demander une réparation'); ?>

                    </a>
                <?php endif; ?>
                <?php if(Auth::user()->hasPermission('gerer_le_materiel')): ?>
                    <a href="<?php echo route('reparation.materiel.index'); ?>"
                            class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <?php echo __("Accéder au matériel de l'entreprise"); ?>

                    </a>
                <?php endif; ?>
                <?php if(Auth::user()->hasPermission('gerer_les_factures_reparations')): ?>
                    <a href="<?php echo route('reparation.facture.index'); ?>"
                            class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <?php echo __("Historique des factures"); ?>

                    </a>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtres et recherche -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden mb-6">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" action="<?php echo e(route('reparation.index')); ?>" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Recherche -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recherche</label>
                                <input type="text" name="search" placeholder="ID, référence matériel, description..."
                                    value="<?php echo e(request('search')); ?>"
                                    oninput="debounceSubmit(this.form)"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                            </div>

                            <!-- Tri par -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trier par</label>
                                <select name="sort_by" onchange="this.form.submit()"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                                    <option value="date" <?php echo e(request('sort_by') === 'date' ? 'selected' : ''); ?>>Date</option>
                                    <option value="status" <?php echo e(request('sort_by') === 'status' ? 'selected' : ''); ?>>Statut</option>
                                </select>
                            </div>

                            <!-- Ordre de tri -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordre</label>
                                <select name="sort_order" onchange="this.form.submit()"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                                    <option value="desc" <?php echo e(request('sort_order') === 'desc' ? 'selected' : ''); ?>>Décroissant</option>
                                    <option value="asc" <?php echo e(request('sort_order') === 'asc' ? 'selected' : ''); ?>>Croissant</option>
                                </select>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Rechercher
                            </button>
                            <a href="<?php echo e(route('reparation.index')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <!-- Onglets -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <div class="flex">
                        <button onclick="switchTab('active')" id="tab-active" class="tab-btn active px-6 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 border-b-2 border-blue-600 dark:border-blue-500 bg-gray-50 dark:bg-gray-700">
                            Réparations actives
                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"><?php echo e(count($activeReparations)); ?></span>
                        </button>
                        <button onclick="switchTab('archived')" id="tab-archived" class="tab-btn px-6 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-900 dark:hover:text-gray-100">
                            Réparations archivées
                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300"><?php echo e(count($archivedReparations)); ?></span>
                        </button>
                    </div>
                </div>

                <!-- Contenu des onglets -->
                <div id="content-active" class="tab-content">
                    <?php if($activeReparations->isEmpty()): ?>
                        <div class="px-4 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-4 text-gray-500 dark:text-gray-400">Aucune réparation active pour le moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Matériel (Référence / Dénomination)
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Description du matériel
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Description du problème
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date de création
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Statut
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php $__currentLoopData = $activeReparations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reparation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition" onclick="window.location='<?php echo e(route('reparation.show', $reparation->id)); ?>'" style="cursor:pointer">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <div class="font-medium"><?php echo e($reparation->materiel->reference ?? '-'); ?></div>
                                                <div class="text-gray-500 dark:text-gray-400"><?php echo e($reparation->materiel->designation ?? '-'); ?></div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                                <?php echo e($reparation->materiel->description ?? '-'); ?>

                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                                <?php echo e($reparation->description); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo e($reparation->created_at->format('d/m/Y H:i')); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <?php
                                                    $statusClass = match($reparation->status) {
                                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                                    };
                                                ?>
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($statusClass); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $reparation->status))); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div id="content-archived" class="tab-content hidden">
                    <?php if($archivedReparations->isEmpty()): ?>
                        <div class="px-4 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-3 3v6m4-6v6" />
                            </svg>
                            <p class="mt-4 text-gray-500 dark:text-gray-400">Aucune réparation archivée.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Matériel (Référence / Dénomination)
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Description du matériel
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Description du problème
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date de création
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Statut
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php $__currentLoopData = $archivedReparations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reparation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition" onclick="window.location='<?php echo e(route('reparation.show', $reparation->id)); ?>'" style="cursor:pointer">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <div class="font-medium"><?php echo e($reparation->materiel->reference ?? '-'); ?></div>
                                                <div class="text-gray-500 dark:text-gray-400"><?php echo e($reparation->materiel->designation ?? '-'); ?></div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                                <?php echo e($reparation->materiel->description ?? '-'); ?>

                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                                <?php echo e($reparation->description); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo e($reparation->created_at->format('d/m/Y H:i')); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <?php
                                                    $statusClass = match($reparation->status) {
                                                        'archived' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                        'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                                    };
                                                ?>
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($statusClass); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $reparation->status))); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Masquer tous les contenus
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-blue-600', 'dark:border-blue-500', 'bg-gray-50', 'dark:bg-gray-700', 'text-gray-900', 'dark:text-gray-100');
                el.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
            });

            // Afficher l'onglet sélectionné
            document.getElementById('content-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
            document.getElementById('tab-' + tab).classList.add('border-blue-600', 'dark:border-blue-500', 'bg-gray-50', 'dark:bg-gray-700', 'text-gray-900', 'dark:text-gray-100');
        }

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

<?php /**PATH /home/vagrant/code/montaza/resources/views/reparation/index.blade.php ENDPATH**/ ?>