<?php

namespace App\LineupScripts;

class Random implements LineupStrategy
{
    /**
     * Fully randomized assignment to heats.
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
