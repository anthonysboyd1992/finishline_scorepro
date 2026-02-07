<?php

namespace App\LineupScripts;

class PillDraw implements LineupStrategy
{
    /**
     * Random draw order for heat positions.
     *
     * @param  array<int, array{id: int, number: string, driver: string, city: string}>  $entries
     * @param  array<string, mixed>  $config
     * @return array<int, array{id: int, number: string, driver: string, city: string}>
     */
    public function order(array $entries, array $config = []): array
    {
        shuffle($entries);

        return $entries;
    }
}
