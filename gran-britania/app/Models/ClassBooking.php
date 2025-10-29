<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClassBooking extends Model
{
    protected $fillable = [
        'class_date',
        'class_time',
        'name',
        'email',
        'user_id',
        'phone',
        'notes',
        'status',
        'gdpr_given',
        'gdpr_at',
        'meeting_url',
    ];

    protected $casts = [
        'gdpr_given' => 'boolean',
        'gdpr_at' => 'datetime',
    ];

    // Relación con el usuario (opcional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
