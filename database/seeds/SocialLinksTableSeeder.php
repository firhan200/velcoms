<?php

use Illuminate\Database\Seeder;

class SocialLinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('social_links')->insert(
            [
                'name' => 'Facebook',
                'icon_name' => 'facebook.png',
                'link' => 'https://facebook.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
        DB::table('social_links')->insert(
            [
                'name' => 'Twitter',
                'icon_name' => 'twitter.png',
                'link' => 'https://twitter.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
        DB::table('social_links')->insert(
            [
                'name' => 'Instagram',
                'icon_name' => 'instagram.png',
                'link' => 'https://instagram.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
        DB::table('social_links')->insert(
            [
                'name' => 'Line',
                'icon_name' => 'line.png',
                'link' => 'https://line.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
        DB::table('social_links')->insert(
            [
                'name' => 'LinkedIn',
                'icon_name' => 'linkedin.png',
                'link' => 'https://linkedin.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
        DB::table('social_links')->insert(
            [
                'name' => 'Skype',
                'icon_name' => 'skype.png',
                'link' => 'https://skype.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
        DB::table('social_links')->insert(
            [
                'name' => 'Telegram',
                'icon_name' => 'telegram.png',
                'link' => 'https://telegram.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
        DB::table('social_links')->insert(
            [
                'name' => 'Whatsapp',
                'icon_name' => 'whatsapp.png',
                'link' => 'https://whatsapp.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
        DB::table('social_links')->insert(
            [
                'name' => 'Youtube',
                'icon_name' => 'youtube.png',
                'link' => 'https://youtube.com/',
                'is_open_newtab' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
    }
}
