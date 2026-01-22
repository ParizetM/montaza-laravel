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
    <?php $__env->startSection('title', 'Détail Devis'); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Détail Devis')); ?> - <?php echo e($devis->reference_projet ?? "Devis #".$devis->id); ?>

            </h2>
            <div>
                <?php if(!$devis->is_archived): ?>
                    <a href="<?php echo e(route('devis_tuyauterie.edit', $devis->id)); ?>" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-500 active:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                        <?php echo e(__('Modifier')); ?>

                    </a>

                    <form method="POST" action="<?php echo e(route('devis_tuyauterie.archive', $devis->id)); ?>" onsubmit="return confirm('Êtes-vous sûr de vouloir archiver ce devis ?');" class="inline-block">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                            <?php echo e(__('Archiver')); ?>

                        </button>
                    </form>
                <?php else: ?>
                    <span class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest mr-2 cursor-not-allowed">
                        <?php echo e(__('Archivé')); ?>

                    </span>
                     <form method="POST" action="<?php echo e(route('devis_tuyauterie.unarchive', $devis->id)); ?>" class="inline-block">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                            <?php echo e(__('Restaurer')); ?>

                        </button>
                    </form>
                <?php endif; ?>

                <a href="<?php echo e(route('devis_tuyauterie.preview', $devis->id)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                    <?php echo e(__('Prévisualiser PDF')); ?>

                </a>
                <a href="<?php echo e(route('devis_tuyauterie.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                    <?php echo e(__('Retour')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Informations Générales -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Informations Devis</h3>
                        <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Référence Projet:</span> <?php echo e($devis->reference_projet ?? 'N/A'); ?></p>
                        <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Lieu Intervention:</span> <?php echo e($devis->lieu_intervention ?? 'N/A'); ?></p>
                        <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Date d'émission:</span> <?php echo e($devis->date_emission ? \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') : '-'); ?></p>
                        <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Validité:</span> <?php echo e($devis->duree_validite); ?> jours (jusqu'au <?php echo e($devis->date_emission ? \Carbon\Carbon::parse($devis->date_emission)->addDays($devis->duree_validite)->format('d/m/Y') : '-'); ?>)</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Informations Client</h3>
                        <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Client:</span> <?php echo e($devis->client_nom); ?></p>
                        <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Contact:</span> <?php echo e($devis->client_contact ?? 'N/A'); ?></p>
                        <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Adresse:</span> <?php echo e($devis->client_adresse ?? 'N/A'); ?></p>
                    </div>
                </div>
                <!-- Conditions -->
                <div class="mt-6 border-t pt-4">
                     <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Conditions de paiement:</span> <?php echo e($devis->conditions_paiement ?? 'Non spécifié'); ?></p>
                     <p class="text-gray-600 dark:text-gray-400"><span class="font-semibold">Délais d'exécution:</span> <?php echo e($devis->delais_execution ?? 'Non spécifié'); ?></p>
                </div>
            </div>

            <!-- Détail des Sections et Lignes -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Détail des Prestations</h3>

                <?php $__currentLoopData = $devis->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $sectionTotal = $section->lignes->sum('total_ht');
                    ?>
                    <div class="mb-8 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                            <h4 class="font-semibold text-gray-900 dark:text-white"><?php echo e($section->titre); ?></h4>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Section: <?php echo e(number_format($sectionTotal, 2, ',', ' ')); ?> €</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-12">#</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Désignation</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matière</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">Qté</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">Unité</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">P.U. HT</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">Total HT</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php $__currentLoopData = $section->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo e($index + 1); ?></td>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                                <div class="font-medium"><?php echo e($ligne->designation); ?></div>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                                <?php echo e($ligne->matiere ?? '-'); ?>

                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right"><?php echo e($ligne->quantite + 0); ?></td> <!-- +0 removes trailing zeros -->
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right"><?php echo e($ligne->unite); ?></td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right"><?php echo e(number_format($ligne->prix_unitaire, 2, ',', ' ')); ?> €</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium"><?php echo e(number_format($ligne->total_ht, 2, ',', ' ')); ?> €</td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Totaux -->
            <div class="flex justify-end">
                <div class="w-full md:w-1/3 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4 sm:p-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Récapitulatif</h3>

                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Total HT</span>
                        <span class="font-medium text-gray-900 dark:text-white"><?php echo e(number_format($devis->total_ht, 2, ',', ' ')); ?> €</span>
                    </div>

                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">TVA (20%)</span>
                        <span class="font-medium text-gray-900 dark:text-white"><?php echo e(number_format($devis->total_tva, 2, ',', ' ')); ?> €</span>
                    </div>

                    <div class="flex justify-between py-2 border-t border-gray-200 dark:border-gray-700 mt-2">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total TTC</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400"><?php echo e(number_format($devis->total_ttc, 2, ',', ' ')); ?> €</span>
                    </div>
                </div>
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
<?php /**PATH /home/vagrant/code/montaza/resources/views/devis_tuyauterie/show.blade.php ENDPATH**/ ?>