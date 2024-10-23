<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Notification;

class Role extends Model
{
    /** @use HasFactory<RoleFactory>  */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
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

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
    public function hasPermission(string $permission): bool
    {
        return $this->permissions->contains('name', $permission);
    }
    public function hasPermissions(array $permissions): bool
    {
        return $this->permissions->whereIn('name', $permissions)->count() === count($permissions);
    }
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions->whereIn('name', $permissions)->count() > 0;
    }
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
