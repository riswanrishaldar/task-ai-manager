<?php

namespace App\Jobs;

use App\Services\AIService;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateTaskAISummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(public int $taskId) {}

    public function handle(
        TaskRepositoryInterface $tasks,
        AIService $aiService
    ): void {
        $task = $tasks->findOrFail($this->taskId);

        $aiData = $aiService->generateSummary($task);

        $tasks->update($task->id, $aiData);
    }
}