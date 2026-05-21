<?php

namespace App\Models;

use App\Exceptions\UserNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    protected $table = 'user_activity';

    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getDailyActivityByUserId(int $userId): array
    {
        if (! User::query()->whereKey($userId)->exists()) {
            throw new UserNotFoundException;
        }

        $logs = self::query()
            ->where('user_id', $userId)
            ->whereDate('started_at', today())
            ->get();

        $activity = collect(range(0, 23))
            ->mapWithKeys(fn (int $hour) => [$hour => 0])
            ->toArray();

        foreach ($logs as $log) {
            $start = $log->started_at->copy();
            $end = $log->ended_at->copy();

            while ($start->lt($end)) {
                $hour = (int) $start->format('G');

                $endOfHour = $start->copy()
                    ->startOfHour()
                    ->addHour();

                $segmentEnd = $end->lt($endOfHour)
                    ? $end
                    : $endOfHour;

                $activity[$hour] += $start->diffInMinutes($segmentEnd);

                $start = $segmentEnd;
            }
        }

        return collect($activity)
            ->map(fn (int $minutes, int $hour) => [
                'hour' => $hour,
                'minutes' => min($minutes, 60),
            ])
            ->values()
            ->toArray();
    }
}
