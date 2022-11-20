<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // User::create([
        //     'employee_id' => 'pirate001',
        //     'department_id' => '1',
        //     'name' => 'Roger',
        //     'email' => 'roger@gmail.com',
        //     'phone' => '09778997079',
        //     'password' => Hash::make('123123123'),
        //     'nrc_number' => 'priate/eb(N) 000001',
        //     'gender' => 'male',
        //     'address' => 'East Blue',
        // ]);
    }
}
