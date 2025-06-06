<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    /** @use HasFactory<\Database\Factories\PaysFactory> */
    use HasFactory;

    protected $fillable = ['nom'];

    public function etablissements()
    {
        return $this->hasMany(Etablissement::class, 'pay_id');
    }
}
