<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class AppsTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        //foreach(range(1, 10) as $index)
        //{
            Apps::create([
                'package' => 'com.lltao.app',
                'title' => '乐乐淘',
                'icon' => 'http://www.lltao.com/assets/image/101.jpg',
                'size' => '10M',
                'images' => '',
                'summary' => '',
                'link' => '',
            ]);
        //}
    }

}
