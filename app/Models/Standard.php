<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    protected $fillable = ['nom'];

    public function matieres()
    {
        return $this->hasManyThrough(Matiere::class, StandardVersion::class);
    }
    public function versions()
    {
        return $this->hasMany(StandardVersion::class);
    }
    public function dossierStandard()
    {
        return $this->belongsTo(DossierStandard::class);
    }

}
