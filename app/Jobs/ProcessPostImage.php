<?php

namespace App\Jobs;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPostImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(public Post $post) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Job Started: Processing images for Post ID {$this->post->id} - '{$this->post->title}'");
        
        try {
            // Simulate image processing effort
            if (empty($this->post->images)) {
                Log::warning("Post ID {$this->post->id} has no images to process.");
                return;
            }

            foreach ($this->post->images as $image) {
                Log::debug("Resizing image: {$image}");
                // In a real app, you'd use a library like Intervention Image here
            }

            sleep(10); // Simulate work
            
            Log::info("Job Completed: Processed images for Post ID {$this->post->id}");
            
        } catch (\Exception $e) {
            Log::error("Job Failed: Could not process images for Post ID {$this->post->id}. Error: " . $e->getMessage());
            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical("Job Permanently Failed: Post ID {$this->post->id}. Reason: " . $exception->getMessage());
    }
}
