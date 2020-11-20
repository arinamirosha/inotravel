<?php

use App\Booking;
use App\BookingHistory;
use App\Events\NewBookingEvent;
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
        // create users and their houses
        $users = factory(User::class, 50)->create()->each(function ($user) {

            // different count of houses for each user
            $houses = factory(House::class, rand(0, 3))->make();
            $user->houses()->saveMany($houses);

            // for each house different facilities and restrictions
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

        // create bookings (each user has different count of bookings to other houses)
        $users->each(function ($user) {
            $notUserHouses = House::all()->where('user_id', '<>', $user->id);

            $bookings = factory(Booking::class, rand(0, 15))->make();
            $bookings->each(function ($booking) use ($user, $notUserHouses) {
                $booking->house_id = $notUserHouses->random()->id;
            });

            $user->bookings()->saveMany($bookings);
        });

        // create history
//        factory(BookingHistory::class, 1500)->create();

        $users->each(function ($user) {
            $user->houses()->each(function ($house) use ($user) {
                $faker = Factory::create();
                $booksIds = $house->bookings()->pluck('id')->toArray();
                if ($booksIds) {
                    $histories = factory(BookingHistory::class, rand(0, 10))->make();
                    $histories->each(function ($history) use ($user, $house, $faker, $booksIds) {
                        $history->booking_id = $faker->randomElement($booksIds);
                    });
                    $user->histories()->saveMany($histories);
                }
            });

            $histories = factory(BookingHistory::class, rand(0, 20))->make();
            $histories->each(function ($history) use ($user) {
                $history->booking_id = Booking::all()->random()->id;
            });
            $user->histories()->saveMany($histories);
        });

    }
}
