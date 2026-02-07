<?php

namespace App\LineupScripts;

use App\Models\RaceResult;

class QualifyingResults implements LineupStrategy
{
    /**
     * Sort entries by their fastest qualifying result time.
     *
     * Queries the race_results table for Qualifying results matching the
     * entry IDs, takes the fastest best_lap_time per entry, and sorts
     * entries fastest first. Entries without qualifying results go to the end.
     *
     * @param  array<int, array{id: int, number: string, driver: string, city: string}>  $entries
     * @param  array<string, mixed>  $config
     * @return array<int, array{id: int, number: string, driver: string, city: string}>
     */
    public function order(array $entries, array $config = []): array
    {
        if (count($entries) === 0) {
            return [];
        }

        $entryIds = array_column($entries, 'id');

        // Get the fastest qualifying time per entry
        $qualifyingTimes = RaceResult::query()
            ->whereIn('entry_id', $entryIds)
            ->where('race_type', 'Qualifying')
            ->whereNotNull('best_lap_time')
            ->selectRaw('entry_id, MIN(best_lap_time) as fastest_time')
            ->groupBy('entry_id')
            ->pluck('fastest_time', 'entry_id')
            ->all();

        usort($entries, function (array $a, array $b) use ($qualifyingTimes): int {
            $timeA = $qualifyingTimes[$a['id']] ?? null;
            $timeB = $qualifyingTimes[$b['id']] ?? null;

            // Entries without a qualifying time go to the end
            if ($timeA === null && $timeB === null) {
                return 0;
            }
            if ($timeA === null) {
                return 1;
            }
            if ($timeB === null) {
                return -1;
            }

            return (float) $timeA <=> (float) $timeB;
        });

        return $entries;
    }
}
