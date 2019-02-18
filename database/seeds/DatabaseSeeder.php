<?php

use Illuminate\Database\Seeder;
use App\Transaction;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Transaction',50)->create();
        // $this->call(UsersTableSeeder::class);
    }
}
