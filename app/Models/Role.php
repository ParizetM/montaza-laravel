<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
    /** @use HasFactory<RoleFactory>  */
    use HasFactory;
    protected $fillable = [
        "name",
    ];
    /**
     * Get all of the users for the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function entite(): BelongsTo
    {
        return $this->belongsTo(Entite::class, 'entite_id');
    }
    public function getIdFromName(string $name): ?int
    {
        $role = $this->where('name', $name)->first();
        return $role ? $role->id : null;
    }
}
