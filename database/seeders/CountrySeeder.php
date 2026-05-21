<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Symfony\Component\Intl\Countries;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Countries::getNames('en') as $isoCode => $name) {
            Country::query()->updateOrCreate(
                ['iso_code' => $isoCode],
                ['name' => $name]
            );
        }
    }
}
