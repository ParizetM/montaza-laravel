<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'matiere_id',
        'quantite',
        'nombre',
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
