<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Famille extends Model
{
    protected $fillable = ['nom'];
    public $timestamps = false;
    public function sousFamilles()
    {
        return $this->hasMany(SousFamille::class)->orderBy('nom');
    }
}
