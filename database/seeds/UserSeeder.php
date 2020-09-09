<?php

use Core\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name'  => 'Dean Simon Damasig',
            'email'  => 'ddamasig@gmail.com',
        ]);
        factory(User::class, 20)->create();
    }
}
