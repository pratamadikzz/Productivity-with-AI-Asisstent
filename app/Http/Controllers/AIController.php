<?php

namespace App\Http\Controllers;

use App\Models\AIConversation;
use App\Services\AI\AIContextService;
use App\Services\AI\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Services\AI\AITaskPriorityService;
use App\Services\AI\AIDailyPlannerService;
use App\Services\AI\AIGoalBreakdownService;
use App\Services\AI\AIWeeklyReviewService;

class AIController extends Controller
{
    public function index(): View
    {
        $conversations = auth()->user()
            ->aiConversations()
            ->latest()
            ->get();

        return view(
            'ai.index',
            compact('conversations')
        );
    }

    public function chat(
        Request $request,
        AIService $aiService,
        AIContextService $contextService,
        AITaskPriorityService $taskPriorityService,
        AIDailyPlannerService $dailyPlannerService,
        AIGoalBreakdownService $goalBreakdownService,
        AIWeeklyReviewService $weeklyReviewService
    ) {
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:2000',
            ],

            'conversation_id' => [
                'nullable',
                'integer',
            ],
        ]);

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Build User Context
        |--------------------------------------------------------------------------
        */


        $context = $contextService->build($user);


        $priorityContext = $taskPriorityService
            ->buildPriorityContext($user);

        $plannerContext = $dailyPlannerService
            ->buildPlannerContext($user);

        $goalContext = $goalBreakdownService
            ->buildGoalBreakdownContext($user);

        $weeklyContext = $weeklyReviewService
            ->buildWeeklyReviewContext($user);

        /*
        |--------------------------------------------------------------------------
        | Get Or Create Conversation
        |--------------------------------------------------------------------------
        */

        if (! empty($validated['conversation_id'])) {

            $conversation = $user
                ->aiConversations()
                ->findOrFail(
                    $validated['conversation_id']
                );
        } else {

            $conversation = $user
                ->aiConversations()
                ->create([
                    'title' => Str::limit(
                        $validated['message'],
                        60
                    ),
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Save User Message
        |--------------------------------------------------------------------------
        */

        $conversation->messages()->create([
            'role' => 'user',
            'content' => $validated['message'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send Message To Gemini
        |--------------------------------------------------------------------------
        */

        try {

            $aiContext = $context . "\n\n"
                . "TASK PRIORITY DATA:\n"
                . $priorityContext . "\n\n"
                . "DAILY PLANNER DATA:\n"
                . $plannerContext . "\n\n"
                . "GOAL BREAKDOWN DATA:\n"
                . $goalContext . "\n\n"
                . "WEEKLY REVIEW DATA:\n"
                . $weeklyContext;

            $result = $aiService->chat(
                $validated['message'],
                $conversation->gemini_interaction_id,
                $aiContext
            );
        } catch (\Throwable $e) {

            \Log::error('AI Chat Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Gemini Interaction ID
        |--------------------------------------------------------------------------
        */

        $conversation->update([
            'gemini_interaction_id' => $result['id'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Save AI Message
        |--------------------------------------------------------------------------
        */

        $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $result['text'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Return JSON Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'conversation_id' => $conversation->id,

            'message' => [
                'role' => 'assistant',
                'content' => $result['text'],
            ],
        ]);
    }

    public function conversation(
        AIConversation $conversation
    ) {
        abort_unless(
            $conversation->user_id === auth()->id(),
            403
        );

        $conversations = auth()->user()
            ->aiConversations()
            ->latest()
            ->get();

        $messages = $conversation
            ->messages()
            ->oldest()
            ->get();

        return view(
            'ai.index',
            compact(
                'conversations',
                'conversation',
                'messages'
            )
        );
    }
}
