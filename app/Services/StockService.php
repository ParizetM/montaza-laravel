<?php

namespace App\Services;

use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class StockService
{
    public function stock(int $matiereId, string $type, int $quantite, ?float $valeurUnitaire = null, ?string $raison = null): JsonResponse
    {
        try {
            $matiere = Matiere::findOrFail($matiereId);
            if ($matiere->typeAffichageStock() == 2) {
                $valeurUnitaire ??= $matiere->ref_valeur_unitaire;

                if (!$valeurUnitaire) {
                    \Log::error('Aucune valeur unitaire définie.', [
                        'matiere_id' => $matiereId,
                        'type' => $type,
                        'quantite' => $quantite
                    ]);
                    return response()->json(['error' => 'Aucune valeur unitaire définie.'], 400);
                }
                $stock = Stock::firstOrCreate(
                    ['matiere_id' => $matiere->id, 'valeur_unitaire' => $valeurUnitaire]
                );
            } else {
                $stock = Stock::firstOrCreate(
                    ['matiere_id' => $matiere->id]
                );
            }

            if ($type == 'entree') {
                $stock->quantite += $quantite;
            } elseif ($type == 'sortie') {
                if ($stock->quantite < $quantite) {
                    \Log::error('Stock insuffisant.', [
                        'matiere_id' => $matiereId,
                        'stock_actuel' => $stock->quantite,
                        'quantite_demandee' => $quantite
                    ]);
                    return response()->json(['error' => 'Stock insuffisant.'], 400);
                }
                $stock->quantite -= $quantite;
            }

            $stock->save();

            MouvementStock::create([
                'matiere_id' => $matiere->id,
                'user_id' => Auth::id(),
                'type' => $type,
                'quantite' => $quantite,
                'valeur_unitaire' => $matiere->typeAffichageStock() == 2 ? $valeurUnitaire : null,
                'raison' => $raison,
                'date' => now(),
            ]);

            return response()->json([
                'message' => 'Mouvement enregistré avec succès.',
                'stock' => $stock
            ]);
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
// app/Services/StockService.php
