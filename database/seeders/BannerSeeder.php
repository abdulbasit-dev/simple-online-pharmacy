<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run()
    {
        DB::table('banners')->delete();

        // create season ticket banners
        $seasonTicketBanners = ['banner-1.png', 'banner-2.png'];
        $firstAdminId = User::role('admin')->value("id");

        foreach ($seasonTicketBanners as $banner) {
            $newBanner = Banner::create([
                'name' => 'season-ticket',
                "created_by" => $firstAdminId,
            ]);

            $path = "/assets/images/banners/season-ticket/" . $banner;
            $newBanner->addMedia(public_path() . $path)->preservingOriginal()->usingName($newBanner->name)->toMediaCollection('season-ticket');
        }
    }
}
