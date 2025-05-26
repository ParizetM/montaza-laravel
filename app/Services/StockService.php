<?php

namespace App\Services;

use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use RuntimeException;
use Log;

class StockService
{
    /**
     * Process stock movement (entry or exit)
     *
     * @param int $matiereId Material ID
     * @param string $type Movement type ('entree' or 'sortie')
     * @param float $quantite Quantity to add or remove
     * @param float|null $valeurUnitaire Unit value (required for type 2 materials)
     * @param string|null $raison Reason for movement
     * @param mixed $cde_ligne_id Order line ID reference
     * @return array Success data
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function stock(int $matiereId, string $type, int $quantite, ?float $valeurUnitaire = null, ?string $raison = null, $cde_ligne_id = null): array
    {
        $matiere = Matiere::findOrFail($matiereId);
        $mouvement = null;
        $mouvement1 = null;

        // Check if this is a material with unit value tracking (type 2)
        if ($matiere->typeAffichageStock() == 2) {
            // Make sure we have a unit value
            $valeurUnitaire = $valeurUnitaire ?? $matiere->ref_valeur_unitaire;
            if (!$valeurUnitaire) {
                throw new InvalidArgumentException('Aucune valeur unitaire définie.');
            }

            // Get or create stock entry for this material with this unit value
            $stock = Stock::where('matiere_id', $matiere->id)
                ->where('valeur_unitaire', $valeurUnitaire)
                ->first() ?? Stock::create([
                    'matiere_id' => $matiere->id,
                    'valeur_unitaire' => $valeurUnitaire,
                    'quantite' => 0
                ]);

            // Process entry
            if ($type == 'entree') {
                // Simply add the quantity to the stock with the specified unit value
                $stock->quantite += $quantite;
                $stock->save();

                // Record the movement
                $mouvement = MouvementStock::create([
                    'matiere_id' => $matiere->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantite' => $quantite,
                    'valeur_unitaire' => $valeurUnitaire,
                    'cde_ligne_id' => $cde_ligne_id,
                    'raison' => $raison,
                    'date' => now(),
                ]);
            }
            // Process exit
            elseif ($type == 'sortie') {
                if ($stock->quantite < $quantite) {
                    throw new RuntimeException('Stock insuffisant.');
                }

                $stock->quantite -= $quantite;
                $stock->save();

                // Record the movement
                $mouvement = MouvementStock::create([
                    'matiere_id' => $matiere->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantite' => $quantite,
                    'valeur_unitaire' => $valeurUnitaire,
                    'cde_ligne_id' => $cde_ligne_id,
                    'raison' => $raison,
                    'date' => now(),
                ]);
            }
        }
        // Simple stock without unit value (type 1)
        else {
            // Get or create stock for this material
            $stock = Stock::firstOrCreate(['matiere_id' => $matiere->id]);

            // Process entry
            if ($type == 'entree') {
                $stock->quantite += $quantite;
                $stock->save();

                // Record the movement
                $mouvement = MouvementStock::create([
                    'matiere_id' => $matiere->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantite' => $quantite,
                    'valeur_unitaire' => $valeurUnitaire,
                    'cde_ligne_id' => $cde_ligne_id,
                    'raison' => $raison,
                    'date' => now(),
                ]);
            }
            // Process exit
            elseif ($type == 'sortie') {
                if ($stock->quantite < $quantite) {
                    throw new RuntimeException('Stock insuffisant.');
                }

                $stock->quantite -= $quantite;
                $stock->save();

                // Record the movement
                $mouvement = MouvementStock::create([
                    'matiere_id' => $matiere->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantite' => $quantite,
                    'valeur_unitaire' => $valeurUnitaire,
                    'cde_ligne_id' => $cde_ligne_id,
                    'raison' => $raison,
                    'date' => now(),
                ]);
            }
        }

        return [
            'mouvement' => $mouvement,
            'mouvement1' => $mouvement1,
        ];
    }

    /**
     * Delete stock movement and adjust inventory
     *
     * @param MouvementStock $mouvement The stock movement to delete
     * @return array Success data
     * @throws RuntimeException
     */
    public function deleteStockFromMouvement(MouvementStock $mouvement): array
    {
        $matiere = $mouvement->matiere;

        // Determine if this is a tracked value material
        if ($matiere->typeAffichageStock() == 2) {
            // Find the corresponding stock entry
            $stock = Stock::where('matiere_id', $matiere->id)
                ->where('valeur_unitaire', $mouvement->valeur_unitaire)
                ->first();

            if (!$stock) {
                throw new RuntimeException('Entrée de stock non trouvée.');
            }

            // Reverse the movement effect
            if ($mouvement->type == 'entree') {
                // If it was an entry, we need to remove that quantity
                if ($stock->quantite < $mouvement->quantite) {
                    Log::error('Stock insuffisant pour annuler le mouvement', [
                        'stock_id' => $stock->id,
                        'mouvement_id' => $mouvement->id,
                        'quantite' => $mouvement->quantite
                    ]);
                    throw new RuntimeException('Impossible d\'annuler le mouvement: stock insuffisant.');
                }
                $stock->quantite -= $mouvement->quantite;
            } else if ($mouvement->type == 'sortie') {
                // If it was an exit, we need to add that quantity back
                $stock->quantite += $mouvement->quantite;
            }

            $stock->save();
        } else {
            // Simple stock handling
            $stock = Stock::where('matiere_id', $matiere->id)->first();

            if (!$stock) {
                throw new RuntimeException('Entrée de stock non trouvée.');
            }

            // Reverse the movement effect
            if ($mouvement->type == 'entree') {
                // If it was an entry, we need to remove that quantity
                if ($stock->quantite < $mouvement->quantite) {
                    Log::error('Stock insuffisant pour annuler le mouvement', [
                        'stock_id' => $stock->id,
                        'mouvement_id' => $mouvement->id,
                        'quantite' => $mouvement->quantite
                    ]);
                    throw new RuntimeException('Impossible d\'annuler le mouvement: stock insuffisant.');
                }
                $stock->quantite -= $mouvement->quantite;
            } else if ($mouvement->type == 'sortie') {
                // If it was an exit, we need to add that quantity back
                $stock->quantite += $mouvement->quantite;
            }

            $stock->save();
        }

        // Delete the movement record
        $mouvement->delete();

        return [
            'message' => 'Mouvement de stock annulé avec succès',
            'stock' => $stock
        ];
    }

    /**
     * Adjust stock unit value for a portion of the stock
     *
     * @param int $stockId Stock entry ID to adjust
     * @param float $quantiteAjuster Quantity to adjust
     * @param float $newValue New unit value (must be lower than current)
     * @param string|null $raison Reason for adjustment
     * @return array Success data
     * @throws InvalidArgumentException
     */
    public function ajusterStock(int $stockId, float $quantiteAjuster, float $newValue, ?string $raison = null): array
    {
        $stock = Stock::findOrFail($stockId);
        $matiere = $stock->matiere;
        $currentValue = $stock->valeur_unitaire;

        // Verify the new value is lower than current value
        if ($newValue >= $currentValue) {
            throw new InvalidArgumentException('La nouvelle valeur doit être inférieure à la valeur actuelle.');
        }

        // Verify the quantity to adjust is valid
        if ($quantiteAjuster <= 0 || $quantiteAjuster > $stock->quantite) {
            throw new InvalidArgumentException('Quantité à ajuster invalide.');
        }

        // Calculate the difference - this is effectively a stock reduction
        $reduction = $currentValue - $newValue;
        $reductionPercentage = ($reduction / $currentValue) * 100;

        // Step 1: Reduce the quantity of the original stock
        $stock->quantite -= $quantiteAjuster;
        $stock->save();

        // Step 2: Create a new stock entry with the adjusted value
        $newStock = Stock::create([
            'matiere_id' => $matiere->id,
            'quantite' => $quantiteAjuster,
            'valeur_unitaire' => $newValue,
        ]);

        // Create a movement record to track this adjustment
        $mouvement = MouvementStock::create([
            'matiere_id' => $matiere->id,
            'user_id' => Auth::id(),
            'type' => 'sortie', // This is effectively a reduction in value
            'quantite' => $quantiteAjuster,
            'valeur_unitaire' => $reduction, // The reduction in unit value
            'raison' => $raison ?? "Ajustement de valeur unitaire ($reductionPercentage%)",
            'date' => now(),
        ]);

        return [
            'message' => 'Valeur unitaire ajustée avec succès',
            'stock_original' => $stock,
            'stock_nouveau' => $newStock,
            'mouvement' => $mouvement
        ];
    }
}
