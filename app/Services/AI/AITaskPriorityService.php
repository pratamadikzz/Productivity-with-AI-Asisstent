<?php

namespace App\Services\AI;

use App\Models\User;

class AITaskPriorityService
{
    public function getTasks(User $user): array
    {
        return $user->tasks()
            ->where('status', '!=', 'completed')
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
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

    public function buildPriorityContext(User $user): string
    {
        $tasks = $this->getTasks($user);

        return json_encode([
            'tasks' => $tasks,
            'instruction' => [
                'Analyze these tasks and determine their priority.',
                'Consider priority level, due date, status, and urgency.',
                'Do not invent information.',
            ],
        ], JSON_PRETTY_PRINT);
    }
}