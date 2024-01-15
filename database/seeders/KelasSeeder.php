<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class KelasSeeder extends Seeder
{


    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $x = 0;
        $dayNamesInBahasa = [
            'Senin', // Monday
            'Selasa', // Tuesday
            'Rabu', // Wednesday
            'Kamis', // Thursday
            'Jumat', // Friday
            'Sabtu', // Saturday
            'Minggu', // Sunday
        ];
        foreach ($this->user() as $item) {
            Kelas::create([
                'title' =>  $item['title'],
                'min' => $faker->randomDigitNot(2),
                'day' => $dayNamesInBahasa[$x],
                'code' =>  $faker->unique()->word,
                'user_id' => 2,
            ]);
            $x++;
        }
    }



    public function user()
    {
        return [
            [
                'title' => '4TI E Pemrograman Object',
            ],
            [
                'title' => '4TI E Sistem Basis Data',
            ],
            [
                'title' => '4TI E Algoritma dan Struktur Data',
            ],
            [
                'title' => '4TI E Jaringan Komputer',
            ],
            [
                'title' => '4TI E Keamanan Informasi',
            ],
            [
                'title' => '4TI E Desain Antarmuka Pengguna',
            ],
            [
                'title' => '4TI E Pemrograman Web',
            ],
        ];
    }
}
