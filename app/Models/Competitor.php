<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'car_number',
        'city',
        'state',
        'country',
        'phone',
        'email',
    ];

    /**
     * Get the competitor's full name.
     */
    public function fullName(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Get the competitor's display name (Last, First).
     */
    public function displayName(): string
    {
        return $this->last_name.', '.$this->first_name;
    }

    /**
     * @return HasMany<Entry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
