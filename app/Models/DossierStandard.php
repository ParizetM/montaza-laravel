<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierStandard extends Model
{
    protected $fillable = ['nom'];

    public function standards()
    {
        return $this->hasMany(Standard::class);
    }
}
