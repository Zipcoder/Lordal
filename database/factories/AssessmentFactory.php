<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
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

$factory->define(App\Assessment::class, function (Faker $faker) {
    return [
    	  "url" => $faker->url
    	, "name" => str_random(20)
    	, "level" => ['Quiz', 'Lab', 'Exam'][$faker->numberBetween(0,2)]
    	, "gradable" => $faker->boolean
        , "max_score" => 100
        , "passing_score" => 60
    	, "assigned_date" => Carbon::now()
    	, "due_date" => Carbon::now()->addDays(7)
    ];
});
