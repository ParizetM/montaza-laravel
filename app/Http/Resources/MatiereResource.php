<?php
namespace App\Http\Resources;

use App\Models\Unite;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MatiereResource extends JsonResource
{
    public function toArray($request)
    {
        $societe_id = $request->input('societe') ?? null;
        $quantite = $this->quantite();
        if ($quantite < 2) {
            $unite_full = $this->unite->full ?? null;
        } else {
            $unite_full = $this->unite->full_plural ?? null;
        }
        return [
            'id' => $this->id,
            'refInterne' => $this->ref_interne,
            'sousFamille' => $this->sousFamille->nom ?? null,
            'quantite' => $quantite,
            'stockMin' => $this->stock_min,
            'designation' => $this->designation,
            'material' => $this->material->nom ?? null,
            'standard' => $this->standardVersion->standard->nom ?? null,
            'standardVersion' => $this->standardVersion->version ?? null,
            'standardPath' => $this->standardVersion->chemin_pdf ?? 'none',
            'dn' => $this->dn ?? null,
            'epaisseur' => $this->epaisseur ?? null,
            'Unite' => $this->unite->short ?? null,
            'Unite_id' => $this->unite->id ?? null,
            'Unite_full' => $unite_full,
            'lastPriceDate' => $this->getLastPrice($societe_id) ? Carbon::parse($this->getLastPrice($societe_id)->date)->format('d/m/Y') : null,
            'lastPrice' => $this->getLastPrice($societe_id) ? $this->getLastPrice($societe_id)->prix_unitaire : null,
            'refexterne' => $this->societeMatiere($societe_id)->ref_externe ?? null,
            'tooltip' => view('components.stock-tooltip', ['matiere' => $this])->render(),
        ];
    }

}
