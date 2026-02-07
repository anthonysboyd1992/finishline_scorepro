<?php

namespace App\Models;

use App\LineupScripts\HeatsByGroup;
use App\LineupScripts\Invert;
use App\LineupScripts\LineupStrategy;
use App\LineupScripts\PillDraw;
use App\LineupScripts\PointsStanding;
use App\LineupScripts\QualifyingResults;
use App\LineupScripts\QualifyingTime;
use App\LineupScripts\Random;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineupScript extends Model
{
    /** @use HasFactory<\Database\Factories\LineupScriptFactory> */
    use HasFactory;

    /**
     * Map of slug to strategy class.
     *
     * @var array<string, class-string<LineupStrategy>>
     */
    private const STRATEGIES = [
        'heats_by_group' => HeatsByGroup::class,
        'pill_draw' => PillDraw::class,
        'qualifying_results' => QualifyingResults::class,
        'qualifying_time' => QualifyingTime::class,
        'points_standing' => PointsStanding::class,
        'invert' => Invert::class,
        'random' => Random::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'race_type',
        'description',
        'icon',
        'is_active',
        'sort_order',
        'config',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'config' => 'array',
        ];
    }

    /**
     * Resolve the strategy class for this script's slug.
     */
    public function resolveStrategy(): LineupStrategy
    {
        $class = self::STRATEGIES[$this->slug] ?? HeatsByGroup::class;

        return new $class;
    }
}
