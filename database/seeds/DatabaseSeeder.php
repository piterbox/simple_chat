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
        //$this->call(UsersTableSeeder::class);
        factory(App\User::class)->create(
            [
                'email' => 'piterbox@mail.com',
                'name' => 'peter'
            ]
        );

        factory(App\User::class)->create(
            [
                'email' => 'vasia@mail.com',
                'name' => 'vasia'
            ]
        );

        factory(App\User::class)->create(
            [
                'email' => 'slava@mail.com',
                'name' => 'slava'
            ]
        );
    }
}
