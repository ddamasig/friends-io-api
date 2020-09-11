<?php

use Core\Models\User;
use Post\Models\Post;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Post\Models\Like;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = factory(Post::class, 15)->create();

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
                    'user_id' => $friend->user_id,
                    'post_id' => $post->getKey()
                ]);
            }

            for ($i = 0; $i < rand(0, 20); $i++) {
                $post->likes()->create([
                    'post_id' => $post->getKey(),
                    'user_id' => $user->getKey()
                ]);
            }
        }
    }
}
