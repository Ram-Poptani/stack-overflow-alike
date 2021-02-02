<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'title' => rtrim( $faker->sentence(rand(5, 10)), '.' ),
        'body' => $faker->paragraphs(rand(3, 7), true),
        'views_count' => rand(0, 10), // Eloquent Event Handling retrieved, creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored.
        'votes_count' => rand(-10, 10),
    ];
});
