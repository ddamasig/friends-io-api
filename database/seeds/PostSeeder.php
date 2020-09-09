<?php

use Post\Models\Post;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = factory(Post::class, 35)->create();

        $faker = Faker::create();

        foreach ($posts as $post) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $imageUrl = $faker->imageUrl(rand(480, 520), rand(320, 360), null);
                $post->addMediaFromUrl($imageUrl)->toMediaCollection('images');
            }
        }
    }
}
