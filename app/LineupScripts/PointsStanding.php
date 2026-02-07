<?php

namespace App\LineupScripts;

class PointsStanding implements LineupStrategy
{
    /**
     * Sort entries by current season points.
     * Placeholder: sorts by driver name alphabetically until real points data exists.
     *
     * @param  array<int, array{id: int, number: string, driver: string, city: string}>  $entries
     * @param  array<string, mixed>  $config
     * @return array<int, array{id: int, number: string, driver: string, city: string}>
     */
    public function order(array $entries, array $config = []): array
    {
        usort($entries, fn (array $a, array $b): int => strcmp($a['driver'], $b['driver']));

        return $entries;
    }
}
