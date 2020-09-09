<?php

use Core\Models\User;
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

            if (rand(0, 1) === 1) {
                $user = factory(User::class)->create();

                $friend = $post->uploader->friends()->create([
                    'user_id' => $user->getKey()
                ]);

                $post->tags()->create([
                    'user_id' => $friend->user_id
                ]);
            }

            User::first()->friends()->create([
                'user_id' => factory(User::class)->create()->getKey()
            ]);
        }
    }
}
