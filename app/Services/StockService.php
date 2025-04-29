<?php

namespace App\Services;

use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class StockService
{
    /**
     * Process stock movement (entry or exit)
     */
    /**
     * Process stock movement (entry or exit)
     *
     * @param int $matiereId Material ID
     * @param string $type Movement type ('entree' or 'sortie')
     * @param int $quantite Quantity to add or remove
     * @param float|null $valeurUnitaire Unit value (required for type 2 materials)
     * @param string|null $raison Reason for movement
     * @param mixed $cde_ligne_id Order line ID reference
     * @return array|JsonResponse Success response or error
     */
    public function stock(int $matiereId, string $type, int $quantite, ?float $valeurUnitaire = null, ?string $raison = null, $cde_ligne_id = null): array|JsonResponse
    {
        try {
            $matiere = Matiere::findOrFail($matiereId);
            $mouvement = null;
            $mouvement1 = null;

            // Check if this is a material with unit value tracking (type 2)
            if ($matiere->typeAffichageStock() == 2) {
                // Make sure we have a unit value
                $valeurUnitaire = $valeurUnitaire ?? $matiere->ref_valeur_unitaire;
                if (!$valeurUnitaire) {
                    return response()->json(['error' => 'Aucune valeur unitaire définie.'], 400);
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
                        return response()->json(['error' => 'Stock insuffisant.'], 400);
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
                        return response()->json(['error' => 'Stock insuffisant.'], 400);
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
        } catch (\Exception $e) {
            \Log::error('Erreur lors du traitement du stock', [
                'exception' => $e->getMessage(),
                'matiere_id' => $matiereId,
                'type' => $type,
                'quantite' => $quantite
            ]);
            return response()->json(['error' => 'Une erreur est survenue.'], 500);
        }
    }


}
