<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserShortcut extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shortcut_id'];

    public function shortcut(): BelongsTo
    {
        return $this->belongsTo(PredefinedShortcut::class);
    }
}
