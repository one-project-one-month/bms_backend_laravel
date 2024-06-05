<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Traits\GenerateCodeNumber;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    use GenerateCodeNumber;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\Admin::factory()->create([
            'username'=> 'ayeaye123',
            'fullName' => 'ayeaye',
            'adminCode' => $this->generateUniqueCode('Adm'),
            'email' => 'aye123@gmail.com',
            'password' => Hash::make('password'),
            'role'=> 'admin',
            

        ]);

        // $this->call([
        //     UserSeeder::class
        // ]);
    }
}
