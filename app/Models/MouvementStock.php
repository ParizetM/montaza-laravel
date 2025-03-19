<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    protected $fillable = [
        'matiere_id',
        'user_id',
        'type',
        'quantite',
        'valeur_unitaire',
        'raison',
        'date',
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

