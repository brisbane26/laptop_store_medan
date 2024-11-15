<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate([
            "role_name" => "Admin"
        ]);

        Role::firstOrCreate([
            "role_name" => "Customer"
        ]);
        
        Role::firstOrCreate([
            "role_name" => "Owner"
        ]);
    }
}
