<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CdeLigne extends Model
{
    /** @use HasFactory<\Database\Factories\CdeLigneFactory> */
    use HasFactory;

    protected $fillable = [
        'cde_id',
        'poste',
        'ref_interne',
        'ref_fournisseur',
        'matiere_id',
        'designation',
        'quantite',
        'unite_id',
        'prix_unitaire',
        'prix',
        'date_livraison',
        'ddp_cde_statut_id',
        'type_expedition_id',
        'date_livraison_reele',
    ];
    public function unite()
    {
        return $this->belongsTo(Unite::class);
    }
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
    public function cde()
    {
        return $this->belongsTo(Cde::class);
    }
    public function ddpCdeStatut()
    {
        return $this->belongsTo(DdpCdeStatut::class);
    }
    public function typeExpedition()
    {
        return $this->belongsTo(TypeExpedition::class);
    }
}
