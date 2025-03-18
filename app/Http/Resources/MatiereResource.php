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
        return [
            'id' => $this->id,
            'refInterne' => $this->ref_interne,
            'sousFamille' => $this->sousFamille->nom ?? null,
            'quantite' => $this->quantite(),
            'designation' => $this->designation,
            'material' => $this->material->nom ?? null,
            'standard' => $this->standardVersion->standard->nom ?? null,
            'standardVersion' => $this->standardVersion->version ?? null,
            'standardPath' => $this->standardVersion->chemin_pdf ?? 'none',
            'dn' => $this->dn ?? null,
            'epaisseur' => $this->epaisseur ?? null,
            'Unite' => $this->unite->short ?? null,
            'Unite_id' => $this->unite->id ?? null,
            'Unite_full' => $this->unite->full ?? null,
            'lastPriceDate' => $this->getLastPrice($societe_id) ? Carbon::parse($this->getLastPrice($societe_id)->pivot->date_dernier_prix)->format('d/m/Y') : null,
            'lastPrice' => $this->getLastPrice($societe_id) ? $this->getLastPrice($societe_id)->pivot->prix : null,
            'lastPriceUnite' => $this->getLastPrice($societe_id) && $this->getLastPrice($societe_id)->pivot->unite_id ? Unite::find($this->getLastPrice($societe_id)->pivot->unite_id)->short : null,
            'lastPriceRefFournisseur' => $this->getLastPrice($societe_id) ? $this->getLastPrice($societe_id)->pivot->ref_fournisseur : null,
        ];
    }

}
