<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Origin;
use App\Models\Type;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $medicines = [
            "Paracetamol",
            "Albuterol",
            "Panadol",
            "Eucerin",
            "Levothyroxine",
            "amoxicillin",
            "vitamin-d",
        ];

        $faker = Factory::create();
        foreach ($medicines as $medicine) {
            $newMedicine = Medicine::create([
                "type_id" => Type::inRandomOrder()->first()->id,
                "origin_id" => Origin::inRandomOrder()->first()->id,
                "name" => $medicine,
                "slug" => Str::slug($medicine),
                "price" => round(rand(500, 100000), -2),
                "quantity" => rand(1, 100),
                "description" => $faker->realText(200),
                "manufacture_at" => $faker->dateTimeBetween('-1 years', 'now'),
                "expire_at" => $faker->dateTimeBetween('now', '+2 years'),
            ]);

            $path = "/assets/images/medicines/" . Str::lower($medicine) . ".jpeg";
            $newMedicine->addMedia(public_path() . $path)->preservingOriginal()->usingName($newMedicine->name)->toMediaCollection();
        }
    }
}
