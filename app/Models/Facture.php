<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'numero_facture',
        'date_emission',
        'montant_total',
        'reparation_id',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'montant_total' => 'decimal:2',
    ];
    public function reparation()
    {
        return $this->belongsTo(Reparation::class);
    }
}
