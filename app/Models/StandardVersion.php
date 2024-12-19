<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandardVersion extends Model
{
    protected $fillable = ['standard_id', 'version'];

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }
}
