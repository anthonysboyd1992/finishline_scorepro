<?php

namespace App\LineupScripts;

class HeatsByGroup implements LineupStrategy
{
    /**
     * Keep entries in their original order (sequential group distribution).
     *
     * @param  array<int, array{id: int, number: string, driver: string, city: string}>  $entries
     * @param  array<string, mixed>  $config
     * @return array<int, array{id: int, number: string, driver: string, city: string}>
     */
    public function order(array $entries, array $config = []): array
    {
        return $entries;
    }
}
