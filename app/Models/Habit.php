<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    protected $fillable = [
        'name',
        'description',
        'frequency',
        'target_per_week',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function getCurrentStreak(): int
    {
        $dates = $this->logs()
            ->orderByDesc('completed_date')
            ->pluck('completed_date')
            ->map(fn($date) => $date->toDateString())
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        $latestDate = \Carbon\Carbon::parse($dates->first());

        // Kalau terakhir check-in bukan hari ini atau kemarin,
        // streak sudah putus.
        if (
            ! $latestDate->isSameDay($today) &&
            ! $latestDate->isSameDay($yesterday)
        ) {
            return 0;
        }

        $streak = 0;
        $expectedDate = $latestDate->copy();

        foreach ($dates as $date) {

            $currentDate = \Carbon\Carbon::parse($date);

            if (! $currentDate->isSameDay($expectedDate)) {
                break;
            }

            $streak++;

            $expectedDate->subDay();
        }

        return $streak;
    }

    public function getLongestStreak(): int
    {
        $dates = $this->logs()
            ->orderBy('completed_date')
            ->pluck('completed_date')
            ->map(fn($date) => $date->toDateString())
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $longest = 0;
        $current = 0;
        $previousDate = null;

        foreach ($dates as $date) {

            $currentDate = \Carbon\Carbon::parse($date);

            if (
                $previousDate &&
                $currentDate->diffInDays($previousDate) === 1
            ) {
                $current++;
            } else {
                $current = 1;
            }

            $longest = max($longest, $current);

            $previousDate = $currentDate;
        }

        return $longest;
    }

    public function getCompletionRate(): int
    {
        $startOfPeriod = now()->startOfWeek();
        $endOfPeriod = now()->endOfWeek();

        $completed = $this->logs()
            ->whereBetween('completed_date', [
                $startOfPeriod->toDateString(),
                $endOfPeriod->toDateString(),
            ])
            ->count();

        $target = $this->target_per_week;

        if ($target <= 0) {
            return 0;
        }

        return min(
            100,
            (int) round(($completed / $target) * 100)
        );
    }

    public function getWeeklyLogs()
    {
        $startOfWeek = now()->startOfWeek();

        return collect(range(0, 6))->map(function ($day) use ($startOfWeek) {

            $date = $startOfWeek->copy()->addDays($day);

            return [
                'date' => $date,
                'completed' => $this->logs()
                    ->whereDate(
                        'completed_date',
                        $date->toDateString()
                    )
                    ->exists(),
            ];
        });
    }
}
