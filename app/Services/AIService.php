<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\TaskPriorityEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIService
{
    public function generateSummary(Task $task): array
    {
        $prompt = $this->buildPrompt($task);

        try {
            if (!config('services.gemini.api_key')) {
                return $this->mockResponse($task);
            }

            // Using the stable Gemini 1.5 Flash endpoint
            $apiKey = config('services.gemini.api_key');
            $model = config('services.gemini.model', 'gemini-1.5-flash');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $response = Http::timeout(20)
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        // Tells Gemini to strictly reply back with a JSON structural layout
                        'responseMimeType' => 'application/json', 
                        'temperature' => 0.3,
                    ]
                ])
                ->throw()
                ->json();

            // Extract text contents from Gemini's nested response structure
            $content = data_get($response, 'candidates.0.content.parts.0.text');
            Log::info('Raw Gemini Response payload:', ['json' => $content]);

            return $this->parseResponse($content, $task);

        } catch (\Throwable $e) {
            // 👇 THIS WILL PRINT THE EXACT API ERROR MESSAGE INTO YOUR LOGS
            Log::error('GEMINI LIVE API ERROR TRACKER:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return $this->mockResponse($task);
        }
    }

    protected function buildPrompt(Task $task): string
    {
        return <<<PROMPT
You are a task analyzer for a task management system.
Analyze the task details provided below and return a valid JSON object.

The JSON object must contain EXACTLY these keys and follow these strict rules:
1. "ai_summary": Must be 1 to 2 sentences, concise, professional, and completely rewritten in different words. Do not copy the title or description verbatim.
2. "ai_priority": Must be an independent evaluation based on details and urgency. Choose exactly one lowercase string value: "low", "medium", or "high". 
3. "rationale": One short sentence explaining your choice.

Do not wrap the output in markdown code blocks or backticks. Return raw valid JSON only.

Task Title: {$task->title}
Task Description: {$task->description}
Selected Priority: {$task->priority}
Due Date: {$task->due_date}
PROMPT;
    }

    protected function parseResponse(string $content, Task $task): array
    {
        $content = trim($content);
        $decoded = json_decode($content, true);

        if (!is_array($decoded)) {
            return $this->mockResponse($task);
        }

        $validPriorities = TaskPriorityEnum::values();
        $aiPriority = strtolower(trim(data_get($decoded, 'ai_priority', '')));

        if (!in_array($aiPriority, $validPriorities)) {
            $aiPriority = $task->priority;
        }

        return [
             'ai_summary'  => data_get($decoded, 'ai_summary', $task->description),
             'ai_priority' => $aiPriority,
             'rationale'   => data_get($decoded, 'rationale', 'No rationale provided by AI.'),
        ];
    }

    protected function mockResponse(Task $task): array
    {
        $summary = Str::limit($task->description ?: $task->title, 80);

        return [
            'ai_summary'  => "AI summary: {$summary}",
            'ai_priority' => $task->priority,
            'rationale'   => 'Fallback response used due to API execution issues.',
        ];
    }
}