<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unite extends Model
{
    protected $fillable = ['libelle'];
    public $timestamps = false;
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }
}
