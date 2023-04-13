<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesTableSeeder::class);
        $this->call(CelebrationsTableSeeder::class);
        $this->call(CitiesSeeder::class);

        $userRole = Role::where('name', 'user')->first();
        $elfRole = Role::where('name', 'elf')->first();
        $adminRole = Role::where('name', 'admin')->first();

        User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ])->roles()->attach($userRole);

        User::create([
            'name' => 'Elf One',
            'email' => 'elf1@example.com',
            'password' => bcrypt('password'),
        ])->roles()->attach($elfRole);

        User::create([
            'name' => 'Admin One',
            'email' => 'admin1@example.com',
            'password' => bcrypt('password'),
        ])->roles()->attach($adminRole);
    }
}
