<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::query()->create([
            "email" => "admin@gmail.com",
            "userName" => "admin",
            "DOB" => "1995-05-18",
            "townshipCode" => "MMR010001",
            "role" => "super admin",
            "password" => Hash::make("12345678"),
        ]);
    }
}
