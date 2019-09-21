<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdministratorsTableSeeder::class);
        $this->call(SocialLinksTableSeeder::class);
        $this->call(ArticleCategoriesTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(SlidersTableSeeder::class);
        $this->call(GalleriesTableSeeder::class);
        $this->call(PhotosTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(AdminNotificationsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
    }
}
