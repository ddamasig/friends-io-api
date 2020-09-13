<?php

use Core\Models\User;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 0; $i < rand(0, 5); $i++) {
                $user->friends()->create([
                    'user_id' => User::where('id', '<>', $user->getKey())
                        ->first()
                        ->getKey()
                ]);
            }
        }
    }
}
