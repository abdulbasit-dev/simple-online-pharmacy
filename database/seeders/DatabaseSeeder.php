<?php

namespace Database\Seeders;

use App\Models\AgeGroup;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // RolePermissionSeeder::class,
            UserSeeder::class,
            TypeSeeder::class,
            OriginSeeder::class,
            MedicineSeeder::class,
            // TeamSeeder::class,
            // SupporterSeeder::class,
            // SectionSeeder::class,
            // AgeGroupSeeder::class,
            // PaymentMethodSeeder::class,
            // CategorySeeder::class,
            // AccountSeeder::class,
            // LeagueSeeder::class,
            // MatchSeeder::class,
            // SeasonTicketSeeder::class,
            // MatchTicketSeeder::class,
            // SaleTypeSeeder::class,
            // BannerSeeder::class,
        ]);
    }
}
