<?php

namespace Database\Seeders;

use App\Models\GlobalSecuritySetting;
use Illuminate\Database\Seeder;

class GlobalSecuritySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'Auto-block Suspicious IPs',
            'Two-Factor Authentication',
            'Rate Limiting',
            'Activity Logging',
        ];

        foreach ($settings as $setting) {
            GlobalSecuritySetting::query()->firstOrCreate([
                'name' => $setting,
                'active' => true,
            ]);
        }
    }
}
