<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AIService
{
    public function chat(
        string $message,
        ?string $previousInteractionId = null,
        ?string $context = null
    ): array {
        $apiKey = config('ai.gemini.api_key');
        $model = config('ai.gemini.model');

        if (! $apiKey) {
            throw new RuntimeException(
                'Gemini API key belum dikonfigurasi.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Build AI Input
        |--------------------------------------------------------------------------
        */

        $input = $message;

        if ($context) {
            $input = <<<PROMPT
You are an AI Productivity Assistant inside a personal productivity application.

You can use the user's productivity data below to answer questions.

IMPORTANT RULES:
- Use the provided data when relevant.
- Do not invent tasks, projects, goals, habits, or notes.
- If the data does not contain the answer, say that you don't have enough information.
- Be concise and practical.
- Prioritize actionable advice.
- The user's data is private and should only be used to help the current user.

USER PRODUCTIVITY DATA:

{$context}

USER MESSAGE:

{$message}
PROMPT;
        }

        /*
        |--------------------------------------------------------------------------
        | Gemini Payload
        |--------------------------------------------------------------------------
        */

        $payload = [
            'model' => $model,
            'input' => $input,
        ];

        if ($previousInteractionId) {
            $payload['previous_interaction_id'] =
                $previousInteractionId;
        }

        /*
        |--------------------------------------------------------------------------
        | Request Gemini
        |--------------------------------------------------------------------------
        */

        $response = Http::withHeaders([
            'x-goog-api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])
            ->connectTimeout(10)
            ->timeout(60)
            ->post(
                'https://generativelanguage.googleapis.com/v1beta/interactions',
                $payload
            );

        /*
        |--------------------------------------------------------------------------
        | Handle Error
        |--------------------------------------------------------------------------
        */

        if ($response->failed()) {
            throw new RuntimeException(
                'Gemini request gagal: ' . $response->body()
            );
        }

        $data = $response->json();

        /*
        |--------------------------------------------------------------------------
        | Return Response
        |--------------------------------------------------------------------------
        */

        return [
            'id' => $data['id'] ?? null,
            'text' => $this->extractText($data),
        ];
    }

    private function extractText(array $data): string
    {
        foreach ($data['steps'] ?? [] as $step) {

            if (
                ($step['type'] ?? null) === 'model_output'
                && isset($step['content'])
                && is_array($step['content'])
            ) {
                foreach ($step['content'] as $content) {

                    if (
                        ($content['type'] ?? null) === 'text'
                        && isset($content['text'])
                    ) {
                        return $content['text'];
                    }
                }
            }
        }

        throw new RuntimeException(
            'Gemini tidak mengembalikan text response.'
        );
    }
}