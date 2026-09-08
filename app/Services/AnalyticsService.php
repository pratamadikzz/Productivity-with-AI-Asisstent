<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class AnalyticsService
{
    public function getOverview(User $user, int $days = 7): array
    {
        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        $endDate = Carbon::today()->endOfDay();

        $startDate = Carbon::today()
            ->subDays($days - 1)
            ->startOfDay();


        /*
        |--------------------------------------------------------------------------
        | Tasks
        |--------------------------------------------------------------------------
        */

        $totalTasks = $user->tasks()->count();

        $completedTasks = $user->tasks()
            ->where('status', 'completed')
            ->count();

        $pendingTasks = $user->tasks()
            ->where('status', '!=', 'completed')
            ->count();

        $taskCompletionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        */

        $totalProjects = $user->projects()->count();

        $activeProjects = $user->projects()
            ->where('status', '!=', 'completed')
            ->count();

        $completedProjects = $user->projects()
            ->where('status', 'completed')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Goals
        |--------------------------------------------------------------------------
        */

        $totalGoals = $user->goals()->count();

        $activeGoals = $user->goals()
            ->where('status', '!=', 'completed')
            ->count();

        $completedGoals = $user->goals()
            ->where('status', 'completed')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Habits
        |--------------------------------------------------------------------------
        */

        $totalHabits = $user->habits()->count();


        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

        $totalNotes = $user->notes()
            ->where('is_archived', false)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Period Tasks
        |--------------------------------------------------------------------------
        */

        $periodCreated = $user->tasks()
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->count();

        $periodCompleted = $user->tasks()
            ->where('status', 'completed')
            ->whereBetween('updated_at', [
                $startDate,
                $endDate,
            ])
            ->count();

        $periodCompletionRate = $periodCreated > 0
            ? round(($periodCompleted / $periodCreated) * 100)
            : 0;

        /*
|--------------------------------------------------------------------------
| Previous Period
|--------------------------------------------------------------------------
*/

        $previousStartDate = $startDate->copy()
            ->subDays($days);

        $previousEndDate = $startDate->copy()
            ->subSecond();

        $previousPeriodCreated = $user->tasks()
            ->whereBetween('created_at', [
                $previousStartDate,
                $previousEndDate,
            ])
            ->count();

        $previousPeriodCompleted = $user->tasks()
            ->where('status', 'completed')
            ->whereBetween('updated_at', [
                $previousStartDate,
                $previousEndDate,
            ])
            ->count();

        $previousPeriodCompletionRate = $previousPeriodCreated > 0
            ? round(
                ($previousPeriodCompleted / $previousPeriodCreated) * 100
            )
            : 0;

        $completionRateChange =
            $periodCompletionRate - $previousPeriodCompletionRate;

        if ($completionRateChange > 0) {
            $comparisonDirection = 'up';
            $comparisonLabel = 'Improved';
        } elseif ($completionRateChange < 0) {
            $comparisonDirection = 'down';
            $comparisonLabel = 'Declined';
        } else {
            $comparisonDirection = 'same';
            $comparisonLabel = 'No Change';
        }

        /*
        |--------------------------------------------------------------------------
        | Daily Task Data
        |--------------------------------------------------------------------------
        */

        $dailyTaskData = [];

        for (
            $date = $startDate->copy();
            $date <= $endDate;
            $date->addDay()
        ) {
            $completed = $user->tasks()
                ->where('status', 'completed')
                ->whereDate(
                    'updated_at',
                    $date->toDateString()
                )
                ->count();

            $dailyTaskData[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'completed' => $completed,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Overdue Tasks
        |--------------------------------------------------------------------------
        */

        $overdueTasks = $user->tasks()
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->whereDate(
                'due_date',
                '<',
                Carbon::today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Most Productive Day
        |--------------------------------------------------------------------------
        */

        $mostProductiveDay = collect($dailyTaskData)
            ->sortByDesc('completed')
            ->first();

        $mostProductiveDayLabel =
            $mostProductiveDay['label'] ?? '-';

        $mostProductiveDayCount =
            $mostProductiveDay['completed'] ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Project Analytics
        |--------------------------------------------------------------------------
        */

        $projects = $user->projects()
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => function ($query) {
                    $query->where('status', 'completed');
                },
            ])
            ->latest()
            ->get();

        foreach ($projects as $project) {
            $project->completion_rate = $project->tasks_count > 0
                ? round(
                    ($project->completed_tasks_count / $project->tasks_count) * 100
                )
                : 0;
        }

        $projectProgress = $projects->count() > 0
            ? round(
                $projects->avg('completion_rate')
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Goal Analytics
        |--------------------------------------------------------------------------
        */

        $goals = $user->goals()
            ->with('milestones')
            ->latest()
            ->get();

        foreach ($goals as $goal) {
            $totalMilestones = $goal->milestones->count();

            $completedMilestones = $goal->milestones
                ->where('is_completed', true)
                ->count();

            $goal->completion_rate = $totalMilestones > 0
                ? round(
                    ($completedMilestones / $totalMilestones) * 100
                )
                : 0;
        }

        $goalProgress = $goals->count() > 0
            ? round(
                $goals->avg('completion_rate')
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Habit Analytics
        |--------------------------------------------------------------------------
        */

        $habits = $user->habits()
            ->with('logs')
            ->latest()
            ->get();

        $habitStartDate = Carbon::now()
            ->subDays(29)
            ->startOfDay();

        $habitEndDate = Carbon::now()
            ->endOfDay();

        $totalHabitChecks = 0;
        $totalHabitExpected = 0;

        foreach ($habits as $habit) {
            $logs = $habit->logs
                ->whereBetween('date', [
                    $habitStartDate->toDateString(),
                    $habitEndDate->toDateString(),
                ]);

            $completedDays = $logs
                ->where('completed', true)
                ->count();

            $expectedDays = 0;

            for (
                $date = $habitStartDate->copy();
                $date <= $habitEndDate;
                $date->addDay()
            ) {
                $expectedDays++;
            }

            $consistency = $expectedDays > 0
                ? round(($completedDays / $expectedDays) * 100)
                : 0;

            $habit->analytics_completed_days = $completedDays;

            $habit->analytics_expected_days = $expectedDays;

            $habit->analytics_consistency = min(
                $consistency,
                100
            );


            /*
            |--------------------------------------------------------------------------
            | Current Streak
            |--------------------------------------------------------------------------
            */

            $streak = 0;

            $currentDate = Carbon::today();

            while (true) {
                $completed = $habit->logs
                    ->where(
                        'date',
                        $currentDate->toDateString()
                    )
                    ->where('completed', true)
                    ->isNotEmpty();

                if (! $completed) {
                    break;
                }

                $streak++;

                $currentDate->subDay();
            }

            $habit->analytics_current_streak = $streak;

            $totalHabitChecks += $completedDays;
            $totalHabitExpected += $expectedDays;
        }

        $overallHabitConsistency = $totalHabitExpected > 0
            ? round(
                ($totalHabitChecks / $totalHabitExpected) * 100
            )
            : 0;

        $bestHabitStreak = $habits->max(
            'analytics_current_streak'
        ) ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Productivity Score
        |--------------------------------------------------------------------------
        |
        | Task Completion  = 40%
        | Habit Consistency = 25%
        | Project Progress  = 20%
        | Overdue Tasks     = 15%
        |
        */

        $taskScore = $periodCompletionRate;

        $habitScore = $overallHabitConsistency;

        $projectScore = $projectProgress;

        if ($overdueTasks === 0) {
            $overdueScore = 100;
        } else {
            $overdueScore = max(
                0,
                100 - ($overdueTasks * 10)
            );
        }

        $productivityScore = round(
            ($taskScore * 0.40) +
                ($habitScore * 0.25) +
                ($projectScore * 0.20) +
                ($overdueScore * 0.15)
        );

        $productivityScore = min(
            max($productivityScore, 0),
            100
        );


        /*
        |--------------------------------------------------------------------------
        | Productivity Score Label
        |--------------------------------------------------------------------------
        */

        if ($productivityScore >= 90) {
            $productivityLabel = 'Excellent';
        } elseif ($productivityScore >= 70) {
            $productivityLabel = 'Great';
        } elseif ($productivityScore >= 50) {
            $productivityLabel = 'Good';
        } elseif ($productivityScore >= 30) {
            $productivityLabel = 'Needs Improvement';
        } else {
            $productivityLabel = 'Getting Started';
        }


        /*
        |--------------------------------------------------------------------------
        | Productivity Health
        |--------------------------------------------------------------------------
        */

        if ($productivityScore >= 90) {
            $productivityHealth = 'excellent';
            $productivityHealthLabel = 'Excellent';
        } elseif ($productivityScore >= 70) {
            $productivityHealth = 'healthy';
            $productivityHealthLabel = 'Healthy';
        } elseif ($productivityScore >= 40) {
            $productivityHealth = 'attention';
            $productivityHealthLabel = 'Needs Attention';
        } else {
            $productivityHealth = 'critical';
            $productivityHealthLabel = 'Critical';
        }


        /*
        |--------------------------------------------------------------------------
        | Automatic Productivity Insights
        |--------------------------------------------------------------------------
        */

        $insights = [];

        if ($periodCompleted > 0) {
            $insights[] = [
                'type' => 'success',
                'title' => 'Productivity',
                'message' => "Kamu menyelesaikan {$periodCompleted} task dalam {$days} hari terakhir.",
            ];
        }

        if ($periodCompletionRate >= 80) {
            $insights[] = [
                'type' => 'success',
                'title' => 'Great Performance',
                'message' => "Completion rate kamu mencapai {$periodCompletionRate}%. Pertahankan konsistensinya!",
            ];
        } elseif ($periodCompletionRate >= 50) {
            $insights[] = [
                'type' => 'info',
                'title' => 'Good Progress',
                'message' => "Completion rate kamu berada di {$periodCompletionRate}%. Masih ada ruang untuk meningkat.",
            ];
        } elseif ($periodCreated > 0) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Task Completion',
                'message' => "Completion rate kamu masih {$periodCompletionRate}%. Coba fokus menyelesaikan task yang paling penting terlebih dahulu.",
            ];
        }

        if ($overdueTasks > 0) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Overdue Tasks',
                'message' => "Kamu masih memiliki {$overdueTasks} task yang melewati deadline.",
            ];
        } else {
            $insights[] = [
                'type' => 'success',
                'title' => 'Deadline',
                'message' => 'Tidak ada task yang melewati deadline.',
            ];
        }

        if ($mostProductiveDayCount > 0) {
            $insights[] = [
                'type' => 'info',
                'title' => 'Most Productive Day',
                'message' => "{$mostProductiveDayLabel} adalah hari paling produktif dengan {$mostProductiveDayCount} task selesai.",
            ];
        }

        if ($totalGoals > 0) {
            $goalMessage = $completedGoals > 0
                ? "Kamu sudah menyelesaikan {$completedGoals} dari {$totalGoals} goal."
                : "Belum ada goal yang selesai dalam daftar kamu.";

            $insights[] = [
                'type' => 'info',
                'title' => 'Goals',
                'message' => $goalMessage,
            ];
        }

        if ($totalHabits > 0) {
            if ($overallHabitConsistency >= 80) {
                $habitMessage = "Habit consistency kamu sangat baik, yaitu {$overallHabitConsistency}%.";
            } elseif ($overallHabitConsistency >= 50) {
                $habitMessage = "Habit consistency kamu berada di {$overallHabitConsistency}%. Terus tingkatkan konsistensinya.";
            } else {
                $habitMessage = "Habit consistency kamu masih {$overallHabitConsistency}%. Coba mulai dari mempertahankan beberapa habit utama.";
            }

            $insights[] = [
                'type' => $overallHabitConsistency >= 80
                    ? 'success'
                    : 'info',
                'title' => 'Habits',
                'message' => $habitMessage,
            ];
        }

        $insights = [];

        /*
|--------------------------------------------------------------------------
| Priority Alerts
|--------------------------------------------------------------------------
*/

        $priorityAlerts = [];

        if ($overdueTasks > 0) {
            $priorityAlerts[] = [
                'type' => 'danger',
                'title' => 'Overdue Tasks',
                'message' => "Ada {$overdueTasks} task yang sudah melewati deadline.",
                'priority' => 1,
            ];
        }

        if ($periodCreated > 0 && $periodCompletionRate < 50) {
            $priorityAlerts[] = [
                'type' => 'warning',
                'title' => 'Low Task Completion',
                'message' => "Completion rate kamu hanya {$periodCompletionRate}%. Fokus selesaikan task yang paling penting.",
                'priority' => 2,
            ];
        }

        if ($totalHabits > 0 && $overallHabitConsistency < 50) {
            $priorityAlerts[] = [
                'type' => 'warning',
                'title' => 'Habit Consistency',
                'message' => "Habit consistency kamu masih {$overallHabitConsistency}%. Coba pertahankan habit utama terlebih dahulu.",
                'priority' => 3,
            ];
        }

        if ($activeGoals > 0 && $goalProgress < 50) {
            $priorityAlerts[] = [
                'type' => 'info',
                'title' => 'Goals Need Attention',
                'message' => "Progress goal kamu masih sekitar {$goalProgress}%. Coba selesaikan milestone berikutnya.",
                'priority' => 4,
            ];
        }

        if ($activeProjects > 0 && $projectProgress < 50) {
            $priorityAlerts[] = [
                'type' => 'info',
                'title' => 'Projects Need Attention',
                'message' => "Progress project aktif kamu masih sekitar {$projectProgress}%.",
                'priority' => 5,
            ];
        }

        usort(
            $priorityAlerts,
            fn($a, $b) => $a['priority'] <=> $b['priority']
        );

        $priorityAlerts = array_slice($priorityAlerts, 0, 3);


        /*
        |--------------------------------------------------------------------------
        | Return Analytics Data
        |--------------------------------------------------------------------------
        */

        return compact(
            'days',

            'startDate',
            'endDate',

            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'taskCompletionRate',

            'totalProjects',
            'activeProjects',
            'completedProjects',

            'totalGoals',
            'activeGoals',
            'completedGoals',

            'totalHabits',

            'totalNotes',

            'periodCreated',
            'periodCompleted',
            'periodCompletionRate',

            'dailyTaskData',

            'overdueTasks',

            'mostProductiveDayLabel',
            'mostProductiveDayCount',

            'projects',
            'projectProgress',

            'goals',
            'goalProgress',

            'habits',
            'overallHabitConsistency',
            'bestHabitStreak',

            'productivityScore',
            'productivityLabel',

            'productivityHealth',
            'productivityHealthLabel',

            'insights',
            'priorityAlerts',

            'previousPeriodCreated',
            'previousPeriodCompleted',
            'previousPeriodCompletionRate',

            'completionRateChange',
            'comparisonDirection',
            'comparisonLabel',
        );
    }
}
