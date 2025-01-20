<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unite extends Model
{
    protected $fillable = ['short', 'full', 'full_plural', 'type'];
    public $timestamps = false;
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }
}
