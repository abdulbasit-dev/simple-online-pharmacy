<?php

namespace Database\Seeders;

use App\Models\Origin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('origins')->delete();

        $countries = ["Turkey", "Iran", "Emirate", "Saudi Arabia"];

        foreach ($countries as $country) {
            Origin::create([
                'name' => $country,
            ]);
        }
    }
}
