<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mailtemplate extends Model
{
    /** @use HasFactory<\Database\Factories\MailtemplateFactory> */
    use HasFactory;
    protected $fillable = ['nom', 'sujet', 'contenu'];
}
