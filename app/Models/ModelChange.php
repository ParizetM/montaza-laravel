<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelChange extends Model
{
    /**@use */
    // use HasFactory;

    protected $fillable = [
        'user_id',
        'model_type',
        'model_id',
        'before',
        'after',
        'event',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
