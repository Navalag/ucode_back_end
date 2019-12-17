<?php

use App\Category;
use App\Comment;
use App\Post;
use App\Role;
use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);

        $faker = Factory::create();
        $users = array_merge(
            factory(User::class, 9)->create()->toArray(),
            factory(User::class, 1)->create(['role_id' => Role::where('name', 'admin')->first()->id])->toArray()
        );
        $categories = factory(Category::class, 6)->create()->toArray();

        foreach ($users as $user) {
            $posts = factory(Post::class, rand(1, 5))->create([
                'user_id' => $user['id'],
                'category_id' => $faker->randomElements($categories)[0]['id'],
            ]);

            foreach ($posts as $post) {
                factory(Comment::class, rand(1, 5))->create([
                    'post_id' => $post['id'],
                    'user_id' => $user['id'],
                ]);
            }
        }
    }
}
