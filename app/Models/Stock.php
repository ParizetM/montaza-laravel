<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'matiere_id',
        'quantite',
        'valeur_unitaire',
        'nombre',
    ];

    protected static function booted(): void
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model): void {
            if ($model->valeur_unitaire == 0 && $model->quantite == 0) {
                $model->delete();
            }
        });

    }
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
