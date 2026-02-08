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
        'competitor_id',
        'event_id',
        'race_class_id',
        'car_number',
        'pill_number',
        'transponder',
        'owner',
        'driver_name',
        'city',
    ];

    /**
     * @return BelongsTo<Competitor, $this>
     */
    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

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
