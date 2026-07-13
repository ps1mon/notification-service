<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Jobs\GenerateReportJob;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function store(StoreReportRequest $request)
    {
        $report = Report::create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        GenerateReportJob::dispatch($report);

        return response()->json($report, 202);
    }

    public function show(Report $report)
    {
        return response()->json($report);
    }

    public function download(Report $report): StreamedResponse
    {
        abort_unless($report->status === 'done' && $report->file_path, 409, 'Report is not ready');
        abort_unless(Storage::disk('local')->exists($report->file_path), 404, 'Report file not found');

        return Storage::disk('local')->download(
            $report->file_path,
            "report_{$report->id}.json"
        );
    }
}
