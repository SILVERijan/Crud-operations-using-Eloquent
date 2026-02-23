@extends('layout')

@section('content')
<div class="row items-center mb-4">
    <div class="col">
        <h1 class="h2 fw-bold text-gradient">My Submitted Forms</h1>
        <p class="text-muted">Review and manage the forms you've submitted.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('customer.forms.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Submit New Form
        </a>
    </div>
</div>

<div class="premium-card shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 border-0">Title</th>
                    <th class="py-3 border-0">Status</th>
                    <th class="py-3 border-0">Submitted At</th>
                    <th class="pe-4 py-3 border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($forms as $form)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold text-dark">{{ $form->title }}</div>
                        </td>
                        <td>
                            @php
                                $statusClass = match($form->status) {
                                    'submitted' => 'bg-info bg-opacity-10 text-info',
                                    'processed' => 'bg-success bg-opacity-10 text-success',
                                    'pending' => 'bg-warning bg-opacity-10 text-warning',
                                    default => 'bg-secondary bg-opacity-10 text-secondary'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 fw-medium text-capitalize">
                                {{ $form->status }}
                            </span>
                        </td>
                        <td>
                            <div class="text-muted small">
                                <i class="bi bi-clock me-1"></i> {{ $form->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('customer.forms.show', $form) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm border-0 bg-light">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <form action="{{ route('customer.forms.destroy', $form) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this form?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm border-0 bg-light">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-file-earmark-text text-muted mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted">You haven't submitted any forms yet.</p>
                                <a href="{{ route('customer.forms.create') }}" class="btn btn-link text-primary text-decoration-none fw-medium">
                                    Submit your first form now
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($forms->hasPages())
        <div class="p-4 border-top bg-light bg-opacity-50">
            {{ $forms->links() }}
        </div>
    @endif
</div>
@endsection
