<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\Admin::factory()->create([
        //     'name' => 'ayeaye',
        //     'password' => Hash::make('password'),
        // ]);
        $this->call([AdminSeeder::class]);
        // $this->call([UserSeeder::class]);
    }
}
