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
    public function stock(int $matiereId, string $type, int $quantite, ?float $valeurUnitaire = null, ?string $raison = null, $cde_ligne_id = null): array|JsonResponse
    {
        try {
            $matiere = Matiere::findOrFail($matiereId);

            // Handle materials with unit value (type 2)
            if ($matiere->typeAffichageStock() == 2) {
                $valeurUnitaire = $valeurUnitaire ?? $matiere->ref_valeur_unitaire;

                if (!$valeurUnitaire) {
                    return response()->json(['error' => 'Aucune valeur unitaire définie.'], 400);
                }

                $stock = Stock::where('matiere_id', $matiere->id)
                    ->where('valeur_unitaire', $valeurUnitaire)
                    ->first() ?? Stock::create([
                        'matiere_id' => $matiere->id,
                        'valeur_unitaire' => $valeurUnitaire,
                        'quantite' => 0
                    ]);
            } else {
                // Simple stock without unit value
                $stock = Stock::firstOrCreate(['matiere_id' => $matiere->id]);
            }

            // Process movement
            if ($type == 'entree') {
                if ($matiere->typeAffichageStock() == 2) {
                    $nombreUnitaires = intval($quantite / $valeurUnitaire);
                    $reste = $quantite % $valeurUnitaire;

                    $stock->quantite += $nombreUnitaires;
                    $mouvement = MouvementStock::create([
                        'matiere_id' => $matiere->id,
                        'user_id' => Auth::id(),
                        'type' => $type,
                        'quantite' => $nombreUnitaires,
                        'valeur_unitaire' => $valeurUnitaire,
                        'cde_ligne_id' => $cde_ligne_id,
                        'raison' => $raison,
                        'date' => now(),
                    ]);
                    if ($reste > 0) {
                        $newstock = Stock::where('matiere_id', $matiere->id)
                            ->where('valeur_unitaire', $reste)
                            ->first();
                        if (!$newstock) {
                            $newstock = Stock::create([
                                'matiere_id' => $matiere->id,
                                'valeur_unitaire' => $reste,
                                'quantite' => 0
                            ]);
                        }
                        $newstock->quantite += 1;
                        $newstock->save();
                        $mouvement1 = MouvementStock::create([
                            'matiere_id' => $matiere->id,
                            'user_id' => Auth::id(),
                            'type' => $type,
                            'quantite' => 1,
                            'valeur_unitaire' => $reste,
                            'cde_ligne_id' => $cde_ligne_id,
                            'raison' => $raison,
                            'date' => now(),
                        ]);
                    }
                } else {
                    $stock->quantite += $quantite;
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
            } elseif ($type == 'sortie') {
                if ($stock->quantite < $quantite) {
                    return response()->json(['error' => 'Stock insuffisant.'], 400);
                }
                $stock->quantite -= $quantite;
            }

            $stock->save();

            // Record movement



            return [
                'mouvement' => $mouvement,
                'mouvement1' => $mouvement1 ?? null,
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
