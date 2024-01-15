<?php

namespace Database\Seeders;

use App\Models\UserKelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->user() as $item) {
            UserKelas::create([
                'kelas_id' => $item['kelas_id'],
                'user_id' => $item['user_id'],
            ]);
        }
        foreach (range(1, 7) as $index1) {
            foreach (range(5, 39) as $index) {
                UserKelas::create([
                    'kelas_id' =>  $index1,
                    'user_id' =>  $index,
                ]);
            }
        }
    }

    public function user()
    {
        return [
            [
                'user_id' => 3,
                'kelas_id' => 1,
            ],
            [
                'user_id' => 4,
                'kelas_id' => 1,
            ],
        ];
    }
}
