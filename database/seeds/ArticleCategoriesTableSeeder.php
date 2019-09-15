<?php

use Illuminate\Database\Seeder;

class ArticleCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('article_categories')->insert(
            [
                'id' => 1,
                'name' => 'Technology',
                'slug' => 'Articles that talking about programming tech.',
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );

        DB::table('article_categories')->insert(
            [
                'id' => 2,
                'name' => 'Foods',
                'slug' => 'Restaurant, Foods & Cafed around the world.',
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
    }
}
