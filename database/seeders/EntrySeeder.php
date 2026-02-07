<?php

namespace Database\Seeders;

use App\Models\Entry;
use App\Models\Event;
use App\Models\RaceClass;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    /**
     * The entries that match the previously hardcoded lineup data.
     *
     * @var array<int, array{number: string, driver: string, city: string}>
     */
    private const ENTRIES = [
        ['number' => '24', 'driver' => 'Allison, Kobe', 'city' => 'Lima, OH'],
        ['number' => '5', 'driver' => 'Christie, D.J.', 'city' => 'Beachville, ON'],
        ['number' => '6', 'driver' => 'Coniam, Ryan', 'city' => 'Burlington, ON'],
        ['number' => '85', 'driver' => 'Daggett, Dustin', 'city' => 'Portland, MI'],
        ['number' => '23', 'driver' => 'Dobie, Devon', 'city' => 'Wapakoneta, OH'],
        ['number' => '12DD', 'driver' => 'Dryden, Darren', 'city' => 'Freelton, ON'],
        ['number' => '7', 'driver' => 'Gledhill, Eric', 'city' => 'Thamesford, ON'],
        ['number' => '7C', 'driver' => 'Gressman, Phil', 'city' => 'Fremont, OH'],
        ['number' => '01', 'driver' => 'Gunn, Tyler', 'city' => 'Napoleon, OH'],
        ['number' => '13', 'driver' => 'Gurley, Van Jr', 'city' => 'Valparaiso, IN'],
        ['number' => '77X', 'driver' => 'Hill, Alex', 'city' => 'Dublin, OH'],
        ['number' => '15', 'driver' => 'Turner, Ryan', 'city' => 'Troy, OH'],
        ['number' => '9', 'driver' => 'Martin, Liam', 'city' => 'Columbus, OH'],
        ['number' => '51X', 'driver' => 'Hickle, J.J.', 'city' => 'Findlay, OH'],
        ['number' => '45', 'driver' => 'Sheridan, Nick', 'city' => 'Toledo, OH'],
        ['number' => '98', 'driver' => 'Trenca, Joe', 'city' => 'Sandusky, OH'],
        ['number' => '77', 'driver' => 'Powless, Tyeller', 'city' => 'Brantford, ON'],
        ['number' => '49', 'driver' => 'Ruhlman, Brian', 'city' => 'Clyde, OH'],
        ['number' => '40X', 'driver' => 'Helms, Caleb', 'city' => 'Findlay, OH'],
        ['number' => '11N', 'driver' => 'Jedrzejek, Kasey', 'city' => 'Toledo, OH'],
        ['number' => '71H', 'driver' => 'Stambaugh, Max', 'city' => 'Elida, OH'],
        ['number' => 'X', 'driver' => 'Keegan, Mike', 'city' => 'London, ON'],
        ['number' => '12B', 'driver' => 'Hall, Luke', 'city' => 'Kitchener, ON'],
        ['number' => '86', 'driver' => 'Pursley, Daison', 'city' => 'Lima, OH'],
        ['number' => '19', 'driver' => 'Mann, Jett', 'city' => 'Findlay, OH'],
        ['number' => '77E', 'driver' => 'VanEvery, Mike', 'city' => 'Brantford, ON'],
        ['number' => '17', 'driver' => 'Horstman, Jared', 'city' => 'Cloverdale, OH'],
        ['number' => '51', 'driver' => 'Haggenbottom, Mike', 'city' => 'London, ON'],
        ['number' => '11H', 'driver' => 'Harmon, Caleb', 'city' => 'Lima, OH'],
        ['number' => '67', 'driver' => 'Martens, Kevin', 'city' => 'Kitchener, ON'],
        ['number' => '16B', 'driver' => 'Williams, Ty', 'city' => 'Dublin, OH'],
        ['number' => '22', 'driver' => 'Shaffer, Aaron', 'city' => 'Springfield, OH'],
    ];

    public function run(): void
    {
        $event = Event::query()->firstOrFail();
        $raceClass = RaceClass::query()->firstOrFail();

        foreach (self::ENTRIES as $data) {
            Entry::query()->updateOrCreate(
                [
                    'event_id' => $event->id,
                    'race_class_id' => $raceClass->id,
                    'car_number' => $data['number'],
                ],
                [
                    'driver_name' => $data['driver'],
                    'city' => $data['city'],
                ],
            );
        }
    }
}
