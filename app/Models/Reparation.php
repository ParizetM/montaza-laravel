<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
        protected $fillable = [
        'id',
        'nom',
        'numero_serie',
        'description',
        'statut',
        'etat',
        'date_creation',
        'date_cloture'
    ];
}
