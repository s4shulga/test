<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = 5;
        $faker = \Faker\Factory::create();

        for($items; $items>0;  $items--) {
            DB::table('profiles')->insert([
                'name' => $faker->name,
            ]);
        }
    }
}
