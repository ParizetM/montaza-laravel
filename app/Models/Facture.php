<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facture extends Model
{
    use HasFactory;

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
