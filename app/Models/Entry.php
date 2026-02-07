<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'race_class_id',
        'car_number',
        'driver_name',
        'city',
    ];

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsTo<RaceClass, $this>
     */
    public function raceClass(): BelongsTo
    {
        return $this->belongsTo(RaceClass::class);
    }

    /**
     * @return HasMany<RaceResult, $this>
     */
    public function raceResults(): HasMany
    {
        return $this->hasMany(RaceResult::class);
    }
}
