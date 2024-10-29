<?php

namespace App\Models;

use Database\Factories\EntiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Role;

class Entite extends Model
{
    /** @use HasFactory<EntiteFactory>  */
    use HasFactory;
    /**
 * @return HasMany<Role, Entite>
     */
    public function roles(): HasMany
    {
            /** @var HasMany<Role, Entite> */
        return $this->hasMany(Role::class);
    }
}
