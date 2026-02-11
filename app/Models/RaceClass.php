<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RaceClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsToMany<Event, $this>
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->withPivot('pill_mode', 'pill_max', 'pill_min', 'transponder_mode', 'competitors_per_row');
    }

    /**
     * @return HasMany<Entry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    /**
     * @return HasOne<RaceClassDefault, $this>
     */
    public function defaultSettings(): HasOne
    {
        return $this->hasOne(RaceClassDefault::class);
    }
}
