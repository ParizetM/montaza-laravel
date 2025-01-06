<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatiereResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'refInterne' => $this->ref_interne,
            'sousFamille' => $this->sousFamille->nom ?? null,
            'designation' => $this->designation,
            'standard' => $this->standard->nom ?? null,
            'standardVersion' => $this->standard->Versions->first()->version ?? null,
            'standardPath' => $this->standard->Versions->first()->chemin_pdf ?? 'none',
            'dn' => $this->dn ?? null,
            'epaisseur' => $this->epaisseur ?? null,
            'Unite' => $this->unite->short ?? null,
        ];

    }
}
