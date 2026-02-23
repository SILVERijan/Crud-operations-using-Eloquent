<?php

use App\Models\Post;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Starting PDF generation test...\n";
    $posts = Post::with(['user', 'categories'])->limit(1)->get();
    
    if ($posts->isEmpty()) {
        echo "No posts found to export.\n";
        exit;
    }

    echo "Post found: " . $posts->first()->title . "\n";
    
    $pdf = Pdf::loadView('admin.pdf.posts', compact('posts'));
    $output = $pdf->output();
    
    echo "PDF output generated. Length: " . strlen($output) . "\n";
    
    $fileName = "exports/debug_test.pdf";
    Storage::disk('public')->put($fileName, $output);
    
    echo "File saved to: " . Storage::disk('public')->path($fileName) . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
