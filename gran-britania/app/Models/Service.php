<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    // Placeholder model in case in the future services are persisted.
    protected $fillable = ['name','type','description','price','duration'];
}
