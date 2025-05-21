<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    /**
     * Get the parent mediaable model.
     */
    public function mediaable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who uploaded the media.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the full path to the media file.
     */
    public function getFullPathAttribute()
    {
        return storage_path('app/public/' . $this->path);
    }

    /**
     * Get the public URL to the media file.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
