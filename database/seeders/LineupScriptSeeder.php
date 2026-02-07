<?php

namespace Database\Seeders;

use App\Models\LineupScript;
use Illuminate\Database\Seeder;

class LineupScriptSeeder extends Seeder
{
    /**
     * Default scripts available per race type.
     *
     * @var array<string, list<string>>
     */
    private const RACE_TYPE_SCRIPTS = [
        'Hotlaps' => ['random', 'pill_draw'],
        'Qualifying' => ['random', 'pill_draw', 'points_standing'],
        'Heat' => ['heats_by_group', 'pill_draw', 'qualifying_results', 'qualifying_time', 'points_standing', 'invert', 'random'],
        'Dash' => ['qualifying_results', 'qualifying_time', 'invert', 'points_standing'],
        'A-Feature' => ['qualifying_results', 'qualifying_time', 'points_standing', 'invert'],
        'B-Feature' => ['qualifying_results', 'qualifying_time', 'points_standing', 'invert'],
    ];

    /**
     * Script definitions keyed by slug.
     *
     * @var array<string, array{name: string, description: string, icon: string}>
     */
    private const SCRIPTS = [
        'heats_by_group' => [
            'name' => 'Heats By Group',
            'description' => 'Distribute entries evenly across heats in order',
            'icon' => 'grid',
        ],
        'pill_draw' => [
            'name' => 'Pill Draw',
            'description' => 'Random draw order for heat positions',
            'icon' => 'shuffle',
        ],
        'qualifying_results' => [
            'name' => 'Qualifying Results',
            'description' => 'Sort entries by fastest qualifying result time',
            'icon' => 'stopwatch',
        ],
        'qualifying_time' => [
            'name' => 'Qualifying Time',
            'description' => 'Sort entries by qualifying times, fastest first',
            'icon' => 'clock',
        ],
        'points_standing' => [
            'name' => 'Points Standing',
            'description' => 'Sort entries by current season points',
            'icon' => 'trophy',
        ],
        'invert' => [
            'name' => 'Invert',
            'description' => 'Reverse the current lineup order in each heat',
            'icon' => 'arrows',
        ],
        'random' => [
            'name' => 'Random',
            'description' => 'Fully randomized assignment to heats',
            'icon' => 'dice',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sortOrder = 1;

        foreach (self::RACE_TYPE_SCRIPTS as $raceType => $slugs) {
            foreach ($slugs as $slug) {
                $script = self::SCRIPTS[$slug];

                LineupScript::query()->updateOrCreate(
                    ['slug' => $slug, 'race_type' => $raceType],
                    [
                        'name' => $script['name'],
                        'description' => $script['description'],
                        'icon' => $script['icon'],
                        'sort_order' => $sortOrder++,
                    ],
                );
            }
        }
    }
}
