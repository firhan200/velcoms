<?php

use Illuminate\Database\Seeder;

class AdminNotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_notifications')->insert(
            [
                'message' => 'John Doe send new contact: wow! very great job with photos, i like it.',
                'link' => '/cms/contacts?show=detail&id=1',
                'is_active' => 1,
                'is_vue_link' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );

        DB::table('admin_notifications')->insert(
            [
                'message' => 'Lyla Smith send new contact: awesome work with articles, can i contact you for further more to work with?',
                'link' => '/cms/contacts?show=detail&id=2',
                'is_active' => 1,
                'is_vue_link' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
    }
}
