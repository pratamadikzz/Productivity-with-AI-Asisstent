<?php

namespace App\Services\AI;

use App\Models\User;

class AIContextService
{
    public function build(User $user): string
    {
        $tasks = $this->getTasks($user);
        $projects = $this->getProjects($user);
        $goals = $this->getGoals($user);
        $habits = $this->getHabits($user);
        $notes = $this->getNotes($user);

        return $this->formatContext(
            $tasks,
            $projects,
            $goals,
            $habits,
            $notes
        );
    }

    private function getTasks(User $user): array
    {
        return $user->tasks()
            ->latest()
            ->limit(20)
            ->get([
                'id',
                'title',
                'status',
                'priority',
                'due_date',
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

    private function getNotes(User $user): array
    {
        return $user->notes()
            ->where('is_archived', false)
            ->latest()
            ->limit(10)
            ->get([
                'id',
                'title',
                'content',
                'is_pinned',
            ])
            ->toArray();
    }

    private function formatContext(
        array $tasks,
        array $projects,
        array $goals,
        array $habits,
        array $notes
    ): string {
        return json_encode([
            'tasks' => $tasks,
            'projects' => $projects,
            'goals' => $goals,
            'habits' => $habits,
            'notes' => $notes,
        ], JSON_PRETTY_PRINT);
    }
}