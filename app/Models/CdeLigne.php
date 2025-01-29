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
}
