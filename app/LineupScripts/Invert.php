<?php

namespace App\LineupScripts;

class Invert implements LineupStrategy
{
    /**
     * Reverse the current lineup order.
     *
     * @param  array<int, array{id: int, number: string, driver: string, city: string}>  $entries
     * @param  array<string, mixed>  $config
     * @return array<int, array{id: int, number: string, driver: string, city: string}>
     */
    public function order(array $entries, array $config = []): array
    {
        return array_reverse($entries);
    }
}
