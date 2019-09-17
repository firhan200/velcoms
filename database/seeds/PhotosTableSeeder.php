<?php

use Illuminate\Database\Seeder;

class PhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('photos')->insert(
            [
                'gallery_id' => 1,
                'image_thumbnail_name' => 'nature1-thumbnail.jpg',
                'image_original_name' => 'nature1.jpg',
                'title' => 'Violet Sky',
                'description' => '<h1>Lorem ipsum dolor sit amet</h1><br/>consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );

        DB::table('photos')->insert(
            [
                'gallery_id' => 1,
                'image_thumbnail_name' => 'nature2-thumbnail.jpg',
                'image_original_name' => 'nature2.jpg',
                'title' => 'Green Forest',
                'description' => '<h1>Lorem ipsum dolor sit amet</h1><br/>consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );

        DB::table('photos')->insert(
            [
                'gallery_id' => 2,
                'image_thumbnail_name' => 'architect1-thumbnail.jpg',
                'image_original_name' => 'architect1.jpg',
                'title' => 'Great Building',
                'description' => '<h1>Lorem ipsum dolor sit amet</h1><br/>consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );

        DB::table('photos')->insert(
            [
                'gallery_id' => 2,
                'image_thumbnail_name' => 'architect2-thumbnail.jpg',
                'image_original_name' => 'architect2.jpg',
                'title' => 'Cozy House',
                'description' => '<h1>Lorem ipsum dolor sit amet</h1><br/>consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
    }
}
