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
        for ($i = 0; $i < 20; $i++) {
            $post = factory(Post::class)->create([
                'uploader_id' => User::all()->random()->getKey()
            ]);
            /**
             * Image service provider is down
             */
            // for ($i = 0; $i < rand(1, 3); $i++) {
            // $imageUrl = sprintf('https://loremflickr.com/%s/%s', rand(320,340), rand(240,260));
            // $post->addMediaFromUrl($imageUrl)->toMediaCollection('images');
            // }

            if ($post->uploader->friends->count()) {
                $friend = $post->uploader
                    ->friends
                    ->random();

                $post->tags()->create([
                    'user_id' => $friend->user_id,
                    'post_id' => $post->getKey()
                ]);

                for ($i = 0; $i < rand(0, 20); $i++) {
                    $post->likes()->create([
                        'post_id' => $post->getKey(),
                        'user_id' => $friend->getKey()
                    ]);
                }
            }
        }
    }
}
