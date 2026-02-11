<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceClassDefault extends Model
{
    protected $fillable = [
        'race_class_id',
        'pill_mode',
        'pill_max',
        'pill_min',
        'transponder_mode',
        'competitors_per_row',
    ];

    /**
     * @return BelongsTo<RaceClass, $this>
     */
    public function raceClass(): BelongsTo
    {
        return $this->belongsTo(RaceClass::class);
    }
}
