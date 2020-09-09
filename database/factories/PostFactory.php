<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Core\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tag;
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
        'description' => $faker->paragraph(rand(3,4)),
        'uploader_id' => factory(User::class)->create()->getKey(),
    ];
});

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'post_id' => factory(Post::class)->create()->getKey(),
        'user_id' => factory(User::class)->create()->getKey(),
    ];
});