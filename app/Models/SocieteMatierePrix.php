<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocieteMatierePrix extends Model
{
    protected $table = 'societe_matiere_prixs';

    protected $fillable = [
        'societe_matiere_id',
        'unite_id',
        'prix_unitaire',
        'taux_conversion_unite',
        'description',
        'date',
        'ddp_ligne_fournisseur_id',
        'cde_ligne_id'
    ];
    public function societeMatiere()
    {
        return $this->belongsTo(SocieteMatiere::class);
    }
    public function unite()
    {
        return $this->belongsTo(Unite::class);
    }
    public function matiere()
    {
        return $this->hasOneThrough(Matiere::class, SocieteMatiere::class, 'id', 'id', 'societe_matiere_id', 'matiere_id');
    }
    public function societe()
    {
        return $this->hasOneThrough(Societe::class, SocieteMatiere::class, 'id', 'id', 'societe_matiere_id', 'societe_id');
    }
}
