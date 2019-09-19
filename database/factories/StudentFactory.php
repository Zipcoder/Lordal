<?php

use Faker\Generator as Faker;

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

$factory->define(App\Student::class, function (Faker $faker) {
    return [
          "cell_number"     => $faker->phoneNumber
        , "github_id"       => $faker->randomNumber
        , "github_username" => $faker->userName
        , "section"         => ""
        , "user_id"         => $faker->randomDigitNotNull
        , "dont_track"      => false
        , "dismissed"       => false
    ];
});
