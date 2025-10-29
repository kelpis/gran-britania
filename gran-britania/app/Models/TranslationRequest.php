<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationRequest extends Model
{
    protected $fillable = [
        'name','email','source_lang','target_lang','urgency','file_path','comments', 'gdpr_given', 'gdpr_at'
    ];
}
