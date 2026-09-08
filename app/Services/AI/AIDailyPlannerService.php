<?php

namespace App\Services\AI;

use App\Models\User;

class AIDailyPlannerService
{
    public function getPlannerData(User $user): array
    {
        return [
            'tasks' => $this->getTasks($user),
            'projects' => $this->getProjects($user),
            'goals' => $this->getGoals($user),
            'habits' => $this->getHabits($user),
        ];
    }

    private function getTasks(User $user): array
    {
        return $user->tasks()
            ->where('status', '!=', 'completed')
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->limit(30)
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

    public function buildPlannerContext(User $user): string
    {
        $data = $this->getPlannerData($user);

        return json_encode([
            'planner_data' => $data,

            'instruction' => [
                'Create a realistic daily plan based on the available data.',
                'Prioritize urgent and high-priority tasks.',
                'Consider task due dates.',
                'Consider active goals and habits.',
                'Do not invent tasks or deadlines.',
                'Do not mark tasks as completed.',
                'The plan is a recommendation only.',
            ],
        ], JSON_PRETTY_PRINT);
    }
}