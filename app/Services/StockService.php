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
     *
     * @param int $matiereId Material ID
     * @param string $type Movement type ('entree' or 'sortie')
     * @param int $quantite Quantity to add or remove
     * @param float|null $valeurUnitaire Unit value (required for type 2 materials)
     * @param string|null $raison Reason for the movement
     * @return JsonResponse
     */
    public function stock(int $matiereId, string $type, int $quantite, ?float $valeurUnitaire = null, ?string $raison = null): JsonResponse
    {
        try {
            $matiere = Matiere::findOrFail($matiereId);

            // Get or create the appropriate stock
            $stock = $this->getOrCreateStock($matiere, $valeurUnitaire);
            if ($stock instanceof JsonResponse) {
                return $stock; // Return error response if any
            }

            // Process the stock movement
            $result = $this->processStockMovement($stock, $type, $quantite);
            if ($result instanceof JsonResponse) {
                return $result; // Return error response if any
            }

            // Record the movement
            $this->recordStockMovement($matiere, $type, $quantite, $valeurUnitaire, $raison);

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

    /**
     * Get or create the appropriate stock based on material type
     *
     * @param Matiere $matiere
     * @param float|null $valeurUnitaire
     * @return Stock|JsonResponse Stock object or error response
     */
    private function getOrCreateStock(Matiere $matiere, ?float $valeurUnitaire)
    {
        if ($matiere->typeAffichageStock() == 2) {
            return $this->getOrCreateStockWithUnitValue($matiere, $valeurUnitaire);
        } else {
            return $this->getOrCreateSimpleStock($matiere);
        }
    }

    /**
     * Get or create stock with unit value
     *
     * @param Matiere $matiere
     * @param float|null $valeurUnitaire
     * @return Stock|JsonResponse
     */
    private function getOrCreateStockWithUnitValue(Matiere $matiere, ?float $valeurUnitaire)
    {
        $valeurUnitaire = $valeurUnitaire ?? $matiere->ref_valeur_unitaire;

        if (!$valeurUnitaire) {
            \Log::error('Aucune valeur unitaire définie.', [
                'matiere_id' => $matiere->id
            ]);
            return response()->json(['error' => 'Aucune valeur unitaire définie.'], 400);
        }

        \Log::info('Recherche du stock existant.', [
            'matiere_id' => $matiere->id,
            'valeur_unitaire' => $valeurUnitaire
        ]);

        $stock = Stock::where('matiere_id', $matiere->id)
            ->where('valeur_unitaire', $valeurUnitaire)
            ->first();

        if (!$stock) {
            \Log::info('Création d\'un nouveau stock.', [
                'matiere_id' => $matiere->id,
                'valeur_unitaire' => $valeurUnitaire
            ]);

            $stock = Stock::create([
                'matiere_id' => $matiere->id,
                'valeur_unitaire' => $valeurUnitaire,
                'quantite' => 0
            ]);
        }

        return $stock;
    }

    /**
     * Get or create simple stock without unit value
     *
     * @param Matiere $matiere
     * @return Stock
     */
    private function getOrCreateSimpleStock(Matiere $matiere)
    {
        \Log::info('Recherche ou création du stock sans valeur unitaire.', [
            'matiere_id' => $matiere->id
        ]);

        return Stock::firstOrCreate(['matiere_id' => $matiere->id]);
    }

    /**
     * Process the stock movement (increase or decrease quantity)
     *
     * @param Stock $stock
     * @param string $type
     * @param int $quantite
     * @return true|JsonResponse True if successful, JsonResponse if error
     */
    private function processStockMovement(Stock $stock, string $type, int $quantite)
    {
        if ($type == 'entree') {
            $stock->quantite += $quantite;
        } elseif ($type == 'sortie') {
            if ($stock->quantite < $quantite) {
                \Log::error('Stock insuffisant.', [
                    'stock_id' => $stock->id,
                    'stock_actuel' => $stock->quantite,
                    'quantite_demandee' => $quantite
                ]);
                return response()->json(['error' => 'Stock insuffisant.'], 400);
            }
            $stock->quantite -= $quantite;
        }

        $stock->save();
        return true;
    }

    /**
     * Record the stock movement
     *
     * @param Matiere $matiere
     * @param string $type
     * @param int $quantite
     * @param float|null $valeurUnitaire
     * @param string|null $raison
     * @return void
     */
    private function recordStockMovement(Matiere $matiere, string $type, int $quantite, ?float $valeurUnitaire, ?string $raison)
    {
        MouvementStock::create([
            'matiere_id' => $matiere->id,
            'user_id' => Auth::id(),
            'type' => $type,
            'quantite' => $quantite,
            'valeur_unitaire' => $matiere->typeAffichageStock() == 2 ? $valeurUnitaire : null,
            'raison' => $raison,
            'date' => now(),
        ]);
    }
}
// app/Services/StockService.php
