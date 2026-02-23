<?php

namespace App\Jobs;

use App\Models\Post;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GeneratePostsPdfJob implements ShouldQueue
{
    use Batchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $postIds;
    protected $batchName;

    /**
     * Create a new job instance.
     */
    public function __construct(array $postIds, string $batchName)
    {
        $this->postIds = $postIds;
        $this->batchName = $batchName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $posts = Post::with(['user', 'categories'])
            ->whereIn('id', $this->postIds)
            ->get();

        $pdf = Pdf::loadView('admin.pdf.posts', compact('posts'));
        
        $fileName = "exports/{$this->batchName}.pdf";
        Storage::disk('public')->put($fileName, $pdf->output());
    }
}
