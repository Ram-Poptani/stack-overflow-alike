<?php

use App\Answer;
use App\Question;
use App\User;
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
        // $this->call(UserSeeder::class);

        /*
        factory(User::class, 5)->create()->each(function ($user) {
            // This body will be calling for every user that is created

            for ($i=0; $i < rand(5, 10); $i++) { 
                $user->questions()
                    ->create( factory( Question::class )
                    ->make()
                    ->toArray() );
            }
        });
        */

        factory(User::class, 5)->create()
                ->each(function ($user){
                    $user->questions()
                        ->saveMany(factory(Question::class, rand(3, 7))->make())
                        ->each(function ($question){
                            $question->answers()->saveMany(factory(Answer::class, rand(2, 7))->make());
                        });
                });
    }
}
