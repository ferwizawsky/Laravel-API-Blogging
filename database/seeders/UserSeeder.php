<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'email' => $item['email'],
                'password' => Hash::make($item['password']),
                'role_id' => $item['role_id'],
            ]);
        }
    }
    public function user()
    {
        return [
            [
                'nama' => 'Admoon',
                'username' => 'admin',
                'email' => 'admin@fioep.com',
                'password' => 'admooners_48',
                'role_id' => 1,
            ],
            [
                'nama' => 'Udeen Winter',
                'username' => 'udeen_winter',
                'email' => 'refeanine@gmail.com',
                'password' => 'udeenwinter_48',
                'role_id' => 0,
            ],
        ];
    }
}
