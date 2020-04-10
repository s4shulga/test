<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileImagesTableSeeder extends Seeder
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
            $id = rand(1,5);
            $path = "public/uploads/profile/$id/";
            $this->createPath($path);
            $image = $faker->image('public/uploads/profile/' . $id,100,100, null, false);
            DB::table('profile_images')->insert([
                'profile_id' => $id,
                'profile_image_path' => "/uploads/profile/$id/$image",
            ]);
        }
    }
    function createPath($path) {

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }
}
