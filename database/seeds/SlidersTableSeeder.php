<?php

use Illuminate\Database\Seeder;

class SlidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sliders')->insert(
            [
                'image_name' => 'slider1.jpg',
                'title' => 'This is example of slider title',
                'sub_title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.',
                'link' => 'http://google.com/',
                'is_text_shown' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );

        DB::table('sliders')->insert(
            [
                'image_name' => 'slider2.jpg',
                'title' => 'This is example of slider title',
                'sub_title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.',
                'link' => 'http://google.com/',
                'is_text_shown' => 0,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
    }
}
