<?php

use App\Facility;
use App\House;
use App\Restriction;
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
        factory(User::class, 5)->create()->each(function ($user) {

            $houses = factory(House::class, 3)->make();
            $user->houses()->saveMany($houses);
            $houses->each(function ($house) {

                $faker = Factory::create();

                $facIds = Facility::pluck('id')->toArray();
                $resIds = Restriction::pluck('id')->toArray();

                $facCountRand = rand(0, count($facIds));
                $resCountRand = rand(0, count($resIds));

                $facilities = $facCountRand ? $faker->unique()->randomElements($facIds, $facCountRand) : [];
                $restrictions = $resCountRand ? $faker->unique()->randomElements($resIds, $resCountRand) : [];

                $house->facilities()->sync($facilities);
                $house->restrictions()->sync($restrictions);
            });
        });
    }
}
