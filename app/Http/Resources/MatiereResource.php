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
            'standard' => $this->standardVersion->standard->nom ?? null,
            'standardVersion' => $this->standardVersion->version ?? null,
            'standardPath' => $this->standardVersion->chemin_pdf ?? 'none',
            'dn' => $this->dn ?? null,
            'epaisseur' => $this->epaisseur ?? null,
            'Unite' => $this->unite->short ?? null,
            'Unite_id' => $this->unite->id ?? null,
            'Unite_full' => $this->unite->full ?? null,
        ];

    }
}
