<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user =User::create([
            "name" => "ali",
            "user_name" => "ali",
            'phone_number'=>'000000001',
            'status'=>0,
            "password" => bcrypt("123456"),
        ]);
    }
}
