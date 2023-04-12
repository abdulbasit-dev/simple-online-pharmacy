<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("types")->delete();

        $medicineTypes = ["Pill", "Liquid", "Injection", "Cream", "Patch", "Suppository", "Inhaler", "Nasal Spray", "Eye Drop", "Ear Drop", "Powder", "Capsule", "Tablet", "Syrup", "Gel", "Ointment", "Drops", "Spray", "Lotion", "Solution", "Suspension", "Aerosol", "Mouthwash", "Lozenge", "Chewable Tablet", "Powder for Solution", "Powder for Suspension", "Powder"];

        foreach ($medicineTypes as $medicineType) {
           Type::create([
               "name" => $medicineType
           ]);
        }
    }
}
