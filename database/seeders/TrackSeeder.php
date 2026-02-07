<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        Track::query()->updateOrCreate(
            ['name' => 'Limaland Motorsports Park'],
            [
                'city' => 'Lima',
                'state' => 'OH',
                'address' => '1400 Bellefontaine Rd',
                'surface_type' => 'dirt',
                'track_length' => '1/4 mile',
            ],
        );
    }
}
