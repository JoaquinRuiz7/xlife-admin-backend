<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            UserSeeder::class,
            PostSeeder::class,
            UserActivitySeeder::class,
            PostCommentsSeeder::class,
            ReportSeeder::class,
            SecurityLogSeeder::class,
            BlockedIpSeeder::class,
            BlockedPhoneSeeder::class,
            GlobalSecuritySettingSeeder::class,
        ]);
    }
}
