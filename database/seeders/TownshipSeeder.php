<?php

namespace Database\Seeders;

use App\Models\Township;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TownshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $towns = Storage::json('public/Township.json');

        foreach($towns as $town){
            Township::query()->updateOrCreate([
                'TownshipId' => $town['TownshipId'],
                'TownshipCode' => $town['TownshipCode'],
                'TownshipName' => $town['TownshipName'],
                'StateCode' => $town['StateCode']
            ]);
        }
    }
}
