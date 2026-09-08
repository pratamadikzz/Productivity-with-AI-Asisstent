<?php

namespace App\Services\AI;

use App\Models\User;
use Carbon\Carbon;

class AIWeeklyReviewService
{
    public function getWeeklyData(User $user): array
    {
        $startOfWeek = Carbon::now()
            ->startOfWeek();

        $endOfWeek = Carbon::now()
            ->endOfWeek();

        return [
            'period' => [
                'start' => $startOfWeek->toDateString(),
                'end' => $endOfWeek->toDateString(),
            ],

            'tasks' => $this->getTasks(
                $user,
                $startOfWeek,
                $endOfWeek
            ),

            'projects' => $this->getProjects($user),

            'goals' => $this->getGoals($user),

            'habits' => $this->getHabits($user),
        ];
    }

    private function getTasks(
        User $user,
        Carbon $start,
        Carbon $end
    ): array {
        return $user->tasks()
            ->where(function ($query) use ($start, $end) {
                $query
                    ->whereBetween(
                        'due_date',
                        [
                            $start->toDateString(),
                            $end->toDateString(),
                        ]
                    )
                    ->orWhere('status', 'completed');
            })
            ->latest()
            ->limit(50)
            ->get([
                'id',
                'title',
                'status',
                'priority',
                'due_date',
                'project_id',
            ])
            ->toArray();
    }

    private function getProjects(User $user): array
    {
        return $user->projects()
            ->latest()
            ->limit(10)
            ->get([
                'id',
                'name',
                'status',
            ])
            ->toArray();
    }

    private function getGoals(User $user): array
    {
        return $user->goals()
            ->with('milestones')
            ->latest()
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getHabits(User $user): array
    {
        return $user->habits()
            ->latest()
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function buildWeeklyReviewContext(
        User $user
    ): string {
        $data = $this->getWeeklyData($user);

        return json_encode([
            'weekly_review' => $data,

            'instruction' => [
                'Review the user productivity for the current week.',
                'Identify completed and incomplete tasks.',
                'Identify overdue or potentially problematic tasks.',
                'Consider project progress.',
                'Consider goal and milestone progress.',
                'Consider habit consistency.',
                'Highlight what went well.',
                'Highlight what needs improvement.',
                'Suggest practical priorities for the next week.',
                'Do not invent data.',
                'Do not modify any user data.',
                'The review is a recommendation only.',
            ],
        ], JSON_PRETTY_PRINT);
    }
}