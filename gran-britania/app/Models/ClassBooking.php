<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassBooking extends Model
{
    protected $fillable = [
        'class_date',
        'class_time',
        'name',
        'email',
        'phone',
        'notes',
        'status'
    ];
}
