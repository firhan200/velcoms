<?php

use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contacts')->insert(
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@gmail.com.',
                'message' => 'wow! very great job with photos, i like it.',
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );

        DB::table('contacts')->insert(
            [
                'id' => 2,
                'name' => 'Lyla Smith',
                'email' => 'lyla.smith@gmail.com.',
                'message' => 'awesome work with articles, can i contact you for further more to work with?',
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        );
    }
}
