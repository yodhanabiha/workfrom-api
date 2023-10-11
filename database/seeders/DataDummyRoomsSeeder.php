<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rooms;
use App\Models\User;
use App\Models\RoomFacilities;
use App\Models\RoomImages;
use App\Models\RoomReviews;
use App\Models\RoomTypes;
use App\Models\RegistrationRoom;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DataDummyRoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Batasi rentang koordinat untuk wilayah Indonesia
        $latitudeMin = -11.00;
        $latitudeMax = 6.00;
        $longitudeMin = 95.00;
        $longitudeMax = 141.00;

        for ($i = 1; $i <= 10; $i++) {
            $latitude = $faker->randomFloat(6, $latitudeMin, $latitudeMax);
            $longitude = $faker->randomFloat(6, $longitudeMin, $longitudeMax);

            $title = $faker->sentence(5);
            $harga = $faker->numberBetween(1000000, 10000000);
            $diskon = $faker->numberBetween(0, 50);
            $capacity = $faker->numberBetween(0, 50);
            $promo = $faker->boolean(30);
            $approved = $faker->boolean(90);
            $mitra = User::where('role', 'Mitra')->inRandomOrder()->first();
            
            Rooms::create([
                'id_user' => $mitra->id,
                'title' => $title,
                'alamat' => $faker->address,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'harga' => $harga,
                'diskon' => $diskon,
                'capacity' => $capacity,
                'promo' => $promo,
                'approved' => $approved,
                'booked' => 0,
                'rating' => 0,
            ]);

            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'User'
            ]);

            $mitra = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'Mitra'
            ]);

            $user->assignRole('user'); 
            $mitra->assignRole('mitra'); 
            $user->save();
            $mitra->save();
        }

        $rooms = Rooms::all();
        

        foreach ($rooms as $room) {

            $numberOfImages = $faker->numberBetween(1, 3);

            RegistrationRoom::create([
                'id_user' => $room->id_user, 
                'id_room' => $room->id,
                'document' => $faker->imageUrl(360, 360)
            ]);

            for ($i = 1; $i <= $numberOfImages; $i++) {

                $user = User::inRandomOrder()->first();

                RoomImages::create([
                    'id_room' => $room->id, 
                    'image' => $faker->imageUrl(360, 360),
                ]);

                RoomTypes::create([
                    'id_room' => $room->id,
                    'type' => $faker->word,
                ]);

                
                RoomReviews::create([
                    'id_room' => $room->id,
                    'id_user' => $user->id,
                    'rating' => $faker->numberBetween(1, 5),
                ]);

            }

            RoomFacilities::create([
                'id_room' => $room->id,
                'internet' => random_int(0, 1),
                'building_access' => random_int(0, 1),
                'print_service' => random_int(0, 1),
                'coffe' => random_int(0, 1),
                'chair' => $faker->numberBetween(0, 10),
                'table' => $faker->numberBetween(0, 10),
                'location_status' => $faker->word,
            ]);

        
            $room->rating = $room->calculateRating();
            $room->save();
        }


    }
}
