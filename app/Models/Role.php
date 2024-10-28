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
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    /** @use HasFactory<RoleFactory>  */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];
    protected static function booted()
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model) {
            self::logChange($model, 'creating');
        });

        // Enregistrer avant la mise à jour d'un modèle
        static::updating(function ($model) {
            self::logChange($model, 'updating');
        });

        // Enregistrer avant la suppression d'un modèle
        static::deleting(function ($model) {
            self::logChange($model, 'deleting');
        });
    }

    protected static function logChange($model, $event)
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Postes',
            'model_id' => $model->getKey(),
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
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
