<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'jesica@gmail.com'],
            [
            "fullname" => "Jesica E Maha",
            "username" => "jesi",
            "email" => "jesica@gmail.com",
            "password" => Hash::make("jesi1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "08123456789123",
            "gender" => "M",
            "address" => "Jalan Pahlawan Gang Revolusi, Sei Putih",
            "role_id" => 1,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        User::firstOrCreate(
            ['email' => 'fatimah@gmail.com'],
            [
            "fullname" => "Fatimah Azzahra",
            "username" => "jara",
            "email" => "fatimah@gmail.com",
            "password" => Hash::make("jara1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "082256648877",
            "gender" => "M",
            "address" => "Jalan Pinang Gang Damai, Pasar 5, Medan Johor",
            "role_id" => 1,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);
        
        User::firstOrCreate(
            ["email" => "brisbanesihombing5@gmail.com"],
            [
            "fullname" => "Brisbane Jovan",
            "username" => "jovan",
            "email" => "brisbanesihombing5@gmail.com",
            "password" => Hash::make("owner123"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "082345659988",
            "gender" => "M",
            "address" => "Jalan Sejati Komplek Citra, Marelan",
            "role_id" => 3,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        User::firstOrCreate(
            ["email" => "member@gmail.com"],
            [
            "fullname" => "Patrick Star",
            "username" => "its_me",
            "email" => "member@gmail.com",
            "password" => Hash::make("1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "082918391823",
            "gender" => "M",
            "address" => "Shell road number 18",
            "role_id" => 2,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        User::firstOrCreate(
            ["email" => "squidy@gmail.com"],
            [
            "fullname" => "Squidy",
            "username" => "omaga",
            "email" => "squidy@gmail.com",
            "password" => Hash::make("1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "019292823382",
            "gender" => "M",
            "address" => "Small healt",
            "role_id" => 2,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        User::firstOrCreate(
            ["email" => "pangeran@gmail.com"],
            [
            "fullname" => "Pangeran Siahaan",
            "username" => "pangeran",
            "email" => "pangeran@gmail.com",
            "password" => Hash::make("pang1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "089287657433",
            "gender" => "M",
            "address" => "Padang Bulan",
            "role_id" => 2,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        User::factory(5)->create();
    }
}
