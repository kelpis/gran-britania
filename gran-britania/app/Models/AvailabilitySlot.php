<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AvailabilitySlot extends Model
{
      protected $fillable = ['date','start_time','end_time','status'];

    public function reservations(): HasMany
    {
        return $this->hasMany(ClassBooking::class, 'availability_slot_id');
    }

    // Slots disponibles (en futuro) y sin reservas confirmadas
    public function scopeOnlyAvailable($q)
    {
        return $q->where('status','available')
                 ->whereDate('date','>=', now()->toDateString());
    }

    public function label(): string
    {
        return sprintf('%s %s–%s', $this->date, substr($this->start_time,0,5), substr($this->end_time,0,5));
    }
}
