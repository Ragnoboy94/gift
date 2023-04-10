<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            ['ru' => 'Москва', 'en' => 'Moscow'],
            ['ru' => 'Санкт-Петербург', 'en' => 'Saint Petersburg'],
            ['ru' => 'Иркутск', 'en' => 'Irkutsk'],
            ['ru' => 'Новосибирск', 'en' => 'Novosibirsk'],
            ['ru' => 'Екатеринбург', 'en' => 'Yekaterinburg'],
            ['ru' => 'Казань', 'en' => 'Kazan'],
            ['ru' => 'Нижний Новгород', 'en' => 'Nizhny Novgorod'],
            ['ru' => 'Челябинск', 'en' => 'Chelyabinsk'],
            ['ru' => 'Омск', 'en' => 'Omsk'],
            ['ru' => 'Самара', 'en' => 'Samara'],
            ['ru' => 'Ростов-на-Дону', 'en' => 'Rostov-on-Don'],
            ['ru' => 'Уфа', 'en' => 'Ufa'],
            ['ru' => 'Красноярск', 'en' => 'Krasnoyarsk'],
            ['ru' => 'Пермь', 'en' => 'Perm'],
            ['ru' => 'Воронеж', 'en' => 'Voronezh'],
            ['ru' => 'Волгоград', 'en' => 'Volgograd'],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name_ru' => $city['ru'],
                'name_en' => $city['en'],
            ]);
        }
    }
}
