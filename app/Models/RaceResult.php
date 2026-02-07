<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_id',
        'race_type',
        'race_number',
        'laps_completed',
        'best_lap_time',
        'total_time',
        'finishing_position',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'best_lap_time' => 'decimal:3',
            'total_time' => 'decimal:3',
        ];
    }

    /**
     * @return BelongsTo<Entry, $this>
     */
    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}
