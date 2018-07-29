<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the users table seed.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name'  => 'Marko Denic',
            'email' => 'marko.denic@web-ideenreich.at'
        ]);
    }
}
