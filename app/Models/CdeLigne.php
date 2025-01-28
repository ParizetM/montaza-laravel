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
}
