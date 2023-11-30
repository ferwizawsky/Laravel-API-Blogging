<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_EN');
        for ($i = 1; $i <= 50; $i++) {
            Event::create([
                'title' =>  $faker->name . " Event",
                'description' => $faker->paragraph,
                'location' => $faker->address,
                'slot' => $faker->numberBetween(25, 50),
                'user_id' =>  $faker->numberBetween(1, 50),
                'time' => date("Y-m-d H:i:s", strtotime("2023-12-29 20:00:00"))
            ]);
        }
    }
}
