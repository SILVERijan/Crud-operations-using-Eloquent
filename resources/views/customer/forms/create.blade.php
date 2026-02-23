@extends('layout')

@section('content')
<div class="article-container py-4">
    <div class="mb-4">
        <a href="{{ route('customer.forms.index') }}" class="text-primary text-decoration-none fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Back to My Forms
        </a>
    </div>

    <div class="premium-card shadow-sm p-4 p-md-5">
        <h1 class="h2 fw-bold text-gradient mb-4">Submit a New Form</h1>
        
        <form action="{{ route('customer.forms.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="form-label fw-semibold">Form Title</label>
                <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                    id="title" name="title" value="{{ old('title') }}" placeholder="Enter a descriptive title" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="form-label fw-semibold">Form Content</label>
                <textarea class="form-control @error('content') is-invalid @enderror" 
                    id="content" name="content" rows="8" placeholder="Enter the details of your request or form information..." required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                <a href="{{ route('customer.forms.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-medium">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm">
                    Submit Form <i class="bi bi-send ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
