@extends('layout')

@section('content')
    <div class="mb-3">
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">&larr; Back to Posts</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h1 class="card-title">{{ $post->title }}</h1>
            <h6 class="card-subtitle mb-3 text-muted">
                Category: {{ $post->category->name }}
                @if($post->published_at)
                    <span class="ms-3">Published: {{ $post->published_at->format('F d, Y') }}</span>
                @endif
            </h6>

            @if($post->images)
                <div class="mb-4">
                    <h5>Images</h5>
                    <div class="row">
                        @foreach($post->images as $image)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Post Image">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card-text">
                {!! \Illuminate\Support\Str::markdown($post->content) !!}
            </div>
        </div>
    </div>
@endsection
