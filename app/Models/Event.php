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
        return $this->belongsToMany(RaceClass::class);
    }

    /**
     * @return HasMany<Entry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
