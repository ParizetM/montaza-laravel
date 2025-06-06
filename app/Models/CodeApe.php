<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeApe extends Model
{
    /** @use HasFactory<\Database\Factories\CodeApeFactory> */
    use HasFactory;

    protected $fillable = ['code', 'nom'];

    public function societes()
    {
        return $this->hasMany(Societe::class, 'code_ape_id');
    }
}
