<?php

namespace App\Http\Resources;

use App\Models\Unite;
use Illuminate\Http\Resources\Json\JsonResource;
use Log;

class MatiereResourceWithPrice extends JsonResource
{
    public function toArray($request)
    {
        $societe_id = $request->input('societe');
        Log::info('societe_id: ' . $societe_id);
        return [
            'id' => $this->id,
            'refInterne' => $this->ref_interne,
            'sousFamille' => $this->sousFamille->nom ?? null,
            'designation' => $this->designation,
            'standard' => $this->standardVersion->standard->nom ?? null,
            'standardVersion' => $this->standardVersion->version ?? null,
            'standardPath' => $this->standardVersion->chemin_pdf ?? 'none',
            'dn' => $this->dn ?? null,
            'epaisseur' => $this->epaisseur ?? null,
            'Unite' => $this->unite->short ?? null,
            'Unite_id' => $this->unite->id ?? null,
            'Unite_full' => $this->unite->full ?? null,
            'lastPriceDate' => $this->getLastPrice($societe_id) ? $this->getLastPrice($societe_id)->pivot->date_dernier_prix : null,
            'lastPrice' => $this->getLastPrice($societe_id) ? $this->getLastPrice($societe_id)->pivot->prix : null,
            'lastPriceUnite' => $this->getLastPrice($societe_id) && $this->getLastPrice($societe_id)->pivot->unite_id ? Unite::find($this->getLastPrice($societe_id)->pivot->unite_id)->short : null,
        ];
    }
}
