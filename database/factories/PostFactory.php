<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Core\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Post\Models\Post;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(rand(1,10)),
        'description' => $faker->paragraph(rand(3,4)),
        'uploader_id' => factory(User::class)->create()->getKey(),
    ];
});
