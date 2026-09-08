<?php

namespace App\Services\AI;

use App\Models\User;

class AIGoalBreakdownService
{
    public function getGoals(User $user): array
    {
        return $user->goals()
            ->with('milestones')
            ->latest()
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function buildGoalBreakdownContext(User $user): string
    {
        $goals = $this->getGoals($user);

        return json_encode([
            'goals' => $goals,

            'instruction' => [
                'Analyze the user goals.',
                'Break large goals into smaller actionable steps.',
                'Use existing milestones when available.',
                'Do not invent existing milestones.',
                'Make the suggested steps realistic and practical.',
                'Do not modify the goals or milestones.',
                'The breakdown is a recommendation only.',
            ],
        ], JSON_PRETTY_PRINT);
    }
}