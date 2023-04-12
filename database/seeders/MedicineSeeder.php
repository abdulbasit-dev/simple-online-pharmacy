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
        $faker = Factory::create();
        foreach (range(1, 10) as $i) {
            $name = $faker->name;
            Medicine::create([
                "type_id" => Type::inRandomOrder()->first()->id,
                "origin_id" => Origin::inRandomOrder()->first()->id,
                "name" => $name,
                "slug" => Str::slug($name),
                "price" => round(rand(500, 100000), -2),
                "quantity" => rand(1, 100),
                // "image" => $faker->imageUrl(),
                "description" => $faker->text,
                "manufacture_at" => $faker->dateTimeBetween('-1 years', 'now'),
                "expire_at" => $faker->dateTimeBetween('now', '+2 years'),
            ]);
        }
    }
}
