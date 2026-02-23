@extends('layout')

@section('content')
<div class="article-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('customer.forms.index') }}" class="text-primary text-decoration-none fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Back to My Forms
        </a>
        
        <div class="col-auto">
            @php
                $statusClass = match($form->status) {
                    'submitted' => 'bg-info bg-opacity-10 text-info',
                    'processed' => 'bg-success bg-opacity-10 text-success',
                    'pending' => 'bg-warning bg-opacity-10 text-warning',
                    default => 'bg-secondary bg-opacity-10 text-secondary'
                };
            @endphp
            <span class="badge {{ $statusClass }} rounded-pill px-4 py-2 fw-medium text-capitalize shadow-sm">
                {{ $form->status }}
            </span>
        </div>
    </div>

    <div class="premium-card shadow-sm p-4 p-md-5 overflow-hidden position-relative">
        <div class="position-absolute top-0 end-0 p-4 opacity-10">
            <i class="bi bi-file-earmark-text" style="font-size: 8rem;"></i>
        </div>
        
        <div class="position-relative">
            <h1 class="h2 fw-bold text-dark mb-2">{{ $form->title }}</h1>
            <div class="d-flex align-items-center text-muted small mb-5">
                <span class="me-3"><i class="bi bi-calendar3 me-1"></i> Submitted: {{ $form->created_at->toFormattedDateString() }}</span>
                <span><i class="bi bi-fingerprint me-1"></i> Form ID: #{{ $form->id }}</span>
            </div>

            <hr class="my-4 opacity-5">

            <div class="post-content bg-light bg-opacity-50 p-4 rounded-4 border border-white">
                {!! nl2br(e($form->content)) !!}
            </div>

            <div class="mt-5 border-top pt-4 d-flex justify-content-end">
                <form action="{{ route('customer.forms.destroy', $form) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this form?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger px-4 rounded-pill border-0 bg-light shadow-sm">
                        <i class="bi bi-trash me-2"></i> Delete Form
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
