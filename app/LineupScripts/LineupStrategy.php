<?php

namespace App\LineupScripts;

interface LineupStrategy
{
    /**
     * Order entries according to this script's algorithm.
     *
     * @param  array<int, array{id: int, number: string, driver: string, city: string}>  $entries
     * @param  array<string, mixed>  $config  Optional config from the database
     * @return array<int, array{id: int, number: string, driver: string, city: string}>
     */
    public function order(array $entries, array $config = []): array;
}
