<?php

namespace App\Models;

use Database\Factories\EntiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entite extends Model
{
    /** @use HasFactory<EntiteFactory>  */
    use HasFactory;
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

}
