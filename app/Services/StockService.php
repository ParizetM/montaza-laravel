<?php

namespace App\Services;

use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class StockService
{
    public function enregistrerMouvement(int $matiereId, string $type, int $quantite, ?float $valeurUnitaire = null, ?string $raison = null): JsonResponse
    {
        $matiere = Matiere::findOrFail($matiereId);
        $valeurUnitaire = $valeurUnitaire ?? $matiere->ref_valeur_unitaire;

        if (!$valeurUnitaire) {
            return response()->json(['error' => 'Aucune valeur unitaire définie.'], 400);
        }

        $stock = Stock::firstOrCreate(
            ['matiere_id' => $matiere->id, 'valeur_unitaire' => $valeurUnitaire]
        );

        if ($type == 'entree') {
            $stock->quantite += $quantite;
        } elseif ($type == 'sortie') {
            if ($stock->quantite < $quantite) {
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
            'valeur_unitaire' => $valeurUnitaire,
            'raison' => $raison,
            'date' => now(),
        ]);

        return response()->json([
            'message' => 'Mouvement enregistré avec succès.',
            'stock' => $stock
        ]);
    }
}
// app/Services/StockService.php
