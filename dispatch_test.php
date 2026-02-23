<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Jobs\GeneratePostsPdfJob;

$post = Post::first();
if ($post) {
    dispatch(new GeneratePostsPdfJob([$post->id], 'test_script'));
    echo "Job dispatched for post ID: " . $post->id . "\n";
} else {
    echo "No posts found\n";
}
