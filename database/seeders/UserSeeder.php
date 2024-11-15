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
            ['email' => 'najibfikri13@gmail.com'],
            [
            "fullname" => "Moh. Najib Fikri",
            "username" => "pStar7",
            "email" => "najibfikri13@gmail.com",
            "password" => Hash::make("1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "08123456789123",
            "gender" => "M",
            "address" => "Shell road number 10",
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
            "password" => Hash::make("4321"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "082345659988",
            "gender" => "M",
            "address" => "Jalan jalan pagi",
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
            "username" => "goodman",
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

        User::factory(5)->create();
    }
}
