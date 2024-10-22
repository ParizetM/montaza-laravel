<?php

namespace App\Models;

use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Notification extends Model
{
    /** @use HasFactory<NotificationFactory>  */
    use HasFactory;
    use SoftDeletes;
    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'role_id',
        'type',
        'data',
        'read',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
