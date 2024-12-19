<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousFamille extends Model
{
    protected $fillable = ['nom', 'famille_id'];
    public $timestamps = false;
    public function famille()
    {
        return $this->belongsTo(Famille::class);
    }
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }
}
