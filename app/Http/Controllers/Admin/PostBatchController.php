<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GeneratePostsPdfJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostBatchController extends Controller
{
    public function export(Request $request)
    {
        $request->validate([
            'post_ids' => 'required|array',
            'post_ids.*' => 'exists:posts,id',
        ]);

        $batchName = 'posts_export_' . Str::random(10);
        
        $batch = Bus::batch([
            new GeneratePostsPdfJob($request->post_ids, $batchName),
        ])->name('PDF Export: ' . $batchName)->dispatch();

        return response()->json([
            'batch_id'       => $batch->id,
            'download_route' => route('admin.posts.export.download', ['file' => $batchName]),
        ]);
    }

    public function exports()
    {
        $files = collect(Storage::disk('public')->files('exports'))
            ->filter(fn($f) => str_ends_with($f, '.pdf'))
            ->map(function ($path) {
                $name = basename($path);
                return [
                    'name'         => $name,
                    'size'         => Storage::disk('public')->size($path),
                    'last_modified'=> Carbon::createFromTimestamp(
                                         Storage::disk('public')->lastModified($path)
                                     )->diffForHumans(),
                    'download_url' => route('admin.posts.export.download', ['file' => pathinfo($name, PATHINFO_FILENAME)]),
                ];
            })
            ->sortByDesc(fn($f) => $f['last_modified'])
            ->values();

        return view('admin.exports', compact('files'));
    }

    public function download(string $file): StreamedResponse
    {
        $path = "exports/{$file}.pdf";

        abort_unless(Storage::disk('public')->exists($path), 404, 'Export file not found.');

        return Storage::disk('public')->download($path, "posts-export.pdf", [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="posts-export.pdf"',
        ]);
    }

    public function checkStatus($batchId)
    {
        return Bus::findBatch($batchId);
    }
}
