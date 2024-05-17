<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{

    use HasUuids;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'id' => 1,
            'accountNo' => Str::uuid()->toString(),
            'username'=>'kkyaw',
            'email'=>'kyawkyaw@gmail.com',
            'phone'=>'094467521987',
            'balance' => 150000,
            'stateCode' => 'MMR010',
            'townshipCode' => 'MMR010001',
            'password' => Hash::make('12345678')
        ]);
    }
}
