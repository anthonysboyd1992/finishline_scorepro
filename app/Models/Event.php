<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
        'name',
        'event_date',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Track, $this>
     */
    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    /**
     * @return BelongsToMany<Sanction, $this>
     */
    public function sanctions(): BelongsToMany
    {
        return $this->belongsToMany(Sanction::class);
    }

    /**
     * @return BelongsToMany<RaceClass, $this>
     */
    public function raceClasses(): BelongsToMany
    {
        return $this->belongsToMany(RaceClass::class)
            ->withPivot('pill_mode', 'pill_max', 'pill_min', 'transponder_mode', 'competitors_per_row');
    }

    /**
     * @return BelongsToMany<Schedule, $this>
     */
    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class);
    }

    /**
     * @return HasMany<Entry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    /**
     * Get a human-readable display name for this event.
     *
     * If name and date are both set, returns "Name - Jan 1, 2026".
     * If only name is set, returns the name.
     * If only date is set, returns the formatted date.
     */
    public function displayName(): string
    {
        $hasName = filled($this->name);
        $hasDate = $this->event_date !== null;

        if ($hasName && $hasDate) {
            return $this->name.' - '.$this->event_date->format('M j, Y');
        }

        if ($hasName) {
            return $this->name;
        }

        if ($hasDate) {
            return $this->event_date->format('M j, Y');
        }

        return 'Untitled Event';
    }
}
