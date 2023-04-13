<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CelebrationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $celebrations = trans('celebrations');
        foreach ($celebrations as $celebration) {
            DB::table('celebrations')->insert([
                'name' => $celebration['name'],
                'image' => $celebration['image'],
                'description' => $celebration['description'],
                'benefits' => json_encode($celebration['benefits']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
