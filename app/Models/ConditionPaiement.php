<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionPaiement extends Model
{
    /** @use HasFactory<\Database\Factories\ConditionPaiementFactory> */
    use HasFactory;

    protected $fillable = ['nom'];

    public function cdes()
    {
        return $this->hasMany(Cde::class);
    }
    public function societes()
    {
        return $this->hasMany(Societe::class);
    }
}
