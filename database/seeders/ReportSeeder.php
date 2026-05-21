<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();

        if ($users->count() < 2) {
            $users = User::factory()->count(10)->create();
        }

        Report::factory()
            ->count(500)
            ->make()
            ->each(function (Report $report) use ($users) {
                $reporter = $users->random();

                $reportedUser = $users
                    ->where('id', '!=', $reporter->id)
                    ->random();

                $report->user_id = $reporter->id;
                $report->reported_user_id = $reportedUser->id;
                $report->save();
            });
    }
}
