<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Posts Export</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .post {
            margin-bottom: 50px;
            page-break-after: always;
        }

        .post:last-child {
            page-break-after: auto;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .meta {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .content {
            font-size: 1.1em;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 0.8em;
            color: #bdc3c7;
        }
    </style>
</head>

<body>
    @foreach($posts as $post)
        <div class="post">
            <h1>{{ $post->title }}</h1>
            <div class="meta">
                By: {{ $post->user->name ?? 'Unknown' }} |
                Published: {{ $post->published_at ? $post->published_at->format('M d, Y') : 'N/A' }} |
                Categories: {{ $post->categories->pluck('name')->implode(', ') }}
            </div>
            <div class="content">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
    @endforeach
    <div class="footer">
        Generated on {{ now()->format('Y-m-d H:i') }}
    </div>

</body>

</html>