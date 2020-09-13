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
        $users = [[
            'name'  => 'John Doe',
            'email' => 'jdoe@safemail.test'
        ], [
            'name'  => 'Kobe Bryant',
            'email' => 'kobebryant@safemail.test'
        ], [
            'name'  => 'LeBron James',
            'email' => 'lebronjames@safemail.test'
        ], [
            'name'  => 'James Harden',
            'email' => 'jamesharden@safemail.test'
        ], [
            'name'  => 'Stephen Curry',
            'email' => 'stephencurry@safemail.test'
        ], [
            'name'  => 'Luka Doncic',
            'email' => 'lukadoncic@safemail.test'
        ], [
            'name'  => 'Russel Westbrook',
            'email' => 'russelwestbrook@safemail.test'
        ], [
            'name'  => 'Manny Pacquiao',
            'email' => 'mannypacquiao@safemail.test'
        ], [
            'name'  => 'Floyd Mayweather',
            'email' => 'floydmayweather@safemail.test'
        ], [
            'name'  => 'Khabib Nurmagomenov',
            'email' => 'khabibnurmagomenov@safemail.test'
        ], [
            'name'  => 'Connor McGregor',
            'email' => 'connormcgregor@safemail.test'
        ], [
            'name'  => 'Tyson Fury',
            'email' => 'tysonfury@safemail.test'
        ], [
            'name'  => 'Anthony Joshua',
            'email' => 'anthonyjoshua@safemail.test'
        ]];

        foreach ($users as $key => $user) {
            factory(User::class)->create([
                'name'  => $user['name'],
                'email' => $user['email']
            ]);
        }
    }
}
