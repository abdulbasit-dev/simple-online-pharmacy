<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        //admin
        User::firstOrCreate(
            [
                "email" => "admin@" . strtolower(str_replace(' ', '', config("app.name"))) . ".com",
                "name" => "admin",
                "phone" => '075043591' . rand(10, 99)
            ],
            [
                "password" => bcrypt("password"),
            ]
        )->assignRole('admin');

        //admin
        User::firstOrCreate(
            [
                "email" => "basit@" . strtolower(str_replace(' ', '', config("app.name"))) . ".com",
                "name" => "basit",
                "phone" => '0750432325',
            ],
            [
                "password" => bcrypt("password"),
            ]
        )->assignRole('admin');
    }
}
