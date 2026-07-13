<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class GenerateReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->report->update(['status' => 'processing']);

        try {
            $stats = Notification::query()
                ->where('user_id', $this->report->user_id)
                ->whereBetween('created_at', [$this->report->period_from, $this->report->period_to])
                ->selectRaw('channel, status, count(*) as cnt')
                ->groupBy('channel', 'status')
                ->get();

            $tmpPath = storage_path("app/reports/tmp_{$this->report->id}.json");
            $finalPath = "reports/{$this->report->id}.json";

            file_put_contents($tmpPath, json_encode($stats));
            Storage::disk('local')->put($finalPath, file_get_contents($tmpPath));
            unlink($tmpPath);

            $this->report->update(['status' => 'done', 'file_path' => $finalPath]);
        } catch (\Throwable $e) {
            $this->report->update(['status' => 'failed', 'error' => $e->getMessage()]);
        }
    }
}
