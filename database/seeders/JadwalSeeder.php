<?php

namespace Database\Seeders;

use App\Models\JadwalUjian;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JadwalUjian::create([
            "day" => date('Y-m-d', strtotime('2024-01-13')),
            "title" => "UTS",
            "kelas_id" => 1,
            "user_id" => 2
        ]);

        JadwalUjian::create([
            "day" => date('Y-m-d', strtotime('2024-01-15')),
            "title" => "UAS",
            "kelas_id" => 1,
            "user_id" => 2
        ]);
    }
}
