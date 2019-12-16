<?php

/** @var Factory $factory */

use App\Category;
use App\Post;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Post::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'category_id' => function() {
            return factory(Category::class)->create()->id;
        },
        'title' => $title,
        'body' => $faker->paragraph,
        'slug' => Str::kebab($title),
        'locked' => false,
    ];
});
