<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = Storage::json('public/State.json');
        // $states = json_decode($jsonData,true);

        foreach($jsonData as $state){
            State::query()->updateOrCreate([
                // 'StateId' => $state['StateId'],
                'stateCode' => $state['StateCode'],
                'stateName' => $state['StateName']
            ]);
        }
    }
}
