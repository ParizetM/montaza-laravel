<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis <?php echo e($devis->reference_projet ?? $devis->id); ?></title>
    <style>
        @page { margin: 0cm; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 2cm; }
        .header { width: 100%; margin-bottom: 30px; }
        .logo { width: 200px; float: left; }
        .company-info { float: right; text-align: right; }
        .client-info { margin-top: 20px; border: 1px solid #ddd; padding: 10px; width: 45%; float: right; }
        .devis-info { margin-top: 20px; width: 50%; float: left; }
        .clear { clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total-section { margin-top: 20px; float: right; width: 40%; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; height: 50px; text-align: center; font-size: 10px; border-top: 1px solid #ddd; padding-top: 10px; }
        .section-title { font-weight: bold; background-color: #e9e9e9; }
    </style>
</head>
<body>
    <div class="header">
        <div class="" style="margin-bottom: 20px;">
            <?php if(isset($entite) && $entite->logo): ?>
                <img src="<?php echo e(public_path($entite->logo)); ?>" alt="Logo" style="width: 200px;">
            <?php else: ?>
                <div class="logo">LOGO</div>
            <?php endif; ?>
        </div>

        <div class="devis-info">
            <h1>DEVIS</h1>
            <p><strong>Référence :</strong> <?php echo e($devis->reference_projet ?? 'N/A'); ?></p>
            <p><strong>Date :</strong> <?php echo e($devis->date_emission ? \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') : date('d/m/Y')); ?></p>
            <p><strong>Validité :</strong> <?php echo e($devis->duree_validite); ?> jours</p>
            <p><strong>Lieu :</strong> <?php echo e($devis->lieu_intervention ?? 'N/A'); ?></p>
        </div>
        <div class="client-info">
            <strong>CLIENT</strong><br>
            <?php echo e($devis->client_nom); ?><br>
            <?php if($devis->client_contact): ?> Attn: <?php echo e($devis->client_contact); ?><br> <?php endif; ?>
            <?php echo nl2br(e($devis->client_adresse)); ?>

        </div>
        <div class="clear"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Prix Unitaire</th>
                <th class="text-right">Total HT</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $devis->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="section-title">
                    <td colspan="4"><?php echo e($section->titre); ?></td>
                </tr>
                <?php $__currentLoopData = $section->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($ligne->designation); ?></td>
                        <td class="text-right"><?php echo e($ligne->quantite); ?> <?php echo e($ligne->unite); ?></td>
                        <td class="text-right"><?php echo e(number_format($ligne->prix_unitaire, 2, ',', ' ')); ?> €</td>
                        <td class="text-right"><?php echo e(number_format($ligne->total_ht, 2, ',', ' ')); ?> €</td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Sous-total <?php echo e($section->titre); ?></strong></td>
                    <td class="text-right"><strong><?php echo e(number_format($section->lignes->sum('total_ht'), 2, ',', ' ')); ?> €</strong></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <td><strong>Total HT</strong></td>
                <td class="text-right"><?php echo e(number_format($devis->sections->flatMap->lignes->sum('total_ht'), 2, ',', ' ')); ?> €</td>
            </tr>
            
        </table>
    </div>

    <div class="clear"></div>

    <div style="margin-top: 40px;">
        <p><strong>Conditions de paiement :</strong> <?php echo e($devis->conditions_paiement ?? 'Non spécifié'); ?></p>
        <p><strong>Délais d'exécution :</strong> <?php echo e($devis->delais_execution ?? 'Non spécifié'); ?></p>
    </div>

    <div class="footer">
        <?php if(isset($entite)): ?>
            <?php echo e(strtoupper($entite->name)); ?> - <?php echo e($entite->adresse); ?> - <?php echo e($entite->code_postal); ?> - <?php echo e($entite->ville); ?> - FRANCE <br>
            Téléphone : <?php echo e($entite->tel); ?> <br>
        <?php endif; ?>
        Document généré le <?php echo e(date('d/m/Y H:i')); ?>

    </div>
</body>
</html>
<?php /**PATH /home/vagrant/code/montaza/resources/views/devis_tuyauterie/pdf.blade.php ENDPATH**/ ?>