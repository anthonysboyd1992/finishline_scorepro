<?php

namespace App\LineupScripts;

class QualifyingTime implements LineupStrategy
{
    /**
     * Sort entries by qualifying times, fastest first.
     * Placeholder: sorts by car number naturally until real qualifying data exists.
     *
     * @param  array<int, array{id: int, number: string, driver: string, city: string}>  $entries
     * @param  array<string, mixed>  $config
     * @return array<int, array{id: int, number: string, driver: string, city: string}>
     */
    public function order(array $entries, array $config = []): array
    {
        usort($entries, fn (array $a, array $b): int => strnatcmp($a['number'], $b['number']));

        return $entries;
    }
}
