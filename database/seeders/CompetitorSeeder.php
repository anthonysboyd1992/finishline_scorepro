<?php

namespace Database\Seeders;

use App\Models\Competitor;
use App\Models\Entry;
use Illuminate\Database\Seeder;

class CompetitorSeeder extends Seeder
{
    public function run(): void
    {
        // Create competitors from existing entries that don't have a competitor yet
        $entries = Entry::query()->whereNull('competitor_id')->get();

        foreach ($entries as $entry) {
            $nameParts = array_map('trim', explode(',', $entry->driver_name));
            $lastName = $nameParts[0] ?? '';
            $firstName = $nameParts[1] ?? '';

            $cityParts = array_map('trim', explode(',', $entry->city ?? ''));
            $city = $cityParts[0] ?? null;
            $state = $cityParts[1] ?? null;

            $competitor = Competitor::query()->firstOrCreate(
                ['first_name' => $firstName, 'last_name' => $lastName],
                [
                    'car_number' => $entry->car_number,
                    'city' => $city,
                    'state' => $state,
                    'country' => 'US',
                ],
            );

            $entry->update(['competitor_id' => $competitor->id]);
        }
    }
}
