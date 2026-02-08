<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transponder extends Model
{
    /** @use HasFactory<\Database\Factories\TransponderFactory> */
    use HasFactory;

    protected $fillable = [
        'number',
        'alias',
        'is_rental',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_rental' => 'boolean',
        ];
    }

    /**
     * Display label: alias if set, otherwise the full number.
     */
    public function displayLabel(): string
    {
        return $this->alias ?: $this->number;
    }
}
