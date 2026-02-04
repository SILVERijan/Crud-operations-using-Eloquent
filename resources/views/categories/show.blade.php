@extends('layout')

@section('content')
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description }}</p>

    <h3>Posts in this category</h3>
    <ul>
        @forelse($category->posts as $post)
            <li><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></li>
        @empty
            <li>No posts found.</li>
        @endforelse
    </ul>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection
