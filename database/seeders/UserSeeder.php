<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->user() as $item) {
            User::create([
                'name' =>  $item['nama'],
                'username' => $item['username'],
                // 'email' => $item['email'],
                'password' => Hash::make($item['password']),
                'role_id' => $item['role_id'],
            ]);
        }
        $faker = Faker::create('id_ID');
        foreach (range(1, 35) as $index) {
            User::create([
                'username' => $faker->unique()->numberBetween(10000000, 99999999),
                'password' => Hash::make("password123"),
                'name' => $faker->name,
                'role_id' => 0,
            ]);
        }
    }
    public function user()
    {
        return [
            [
                'nama' => 'Admoon',
                'username' => 'admin',
                'password' => 'admooners_48',
                'role_id' => 2,
            ],
            [
                'nama' => 'Dosen Trial',
                'username' => 'dosen1',
                'password' => 'dosen1',
                'role_id' => 1,
            ],
            [
                'nama' => 'Udeen Winter',
                'username' => 'udeen_winter',
                'password' => 'udeenwinter_48',
                'role_id' => 0,
            ],
            [
                'nama' => 'Wela Winter',
                'username' => 'wela_winter',
                'password' => 'welawinter_48',
                'role_id' => 0,
            ],
        ];
    }
}
