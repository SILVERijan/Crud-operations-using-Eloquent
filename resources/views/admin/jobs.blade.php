@extends('layout')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Background Jobs</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <!-- Pending Jobs -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Pending Jobs ({{ $pendingJobs->count() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Queue</th>
                            <th>Attempts</th>
                            <th>Available At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingJobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->queue }}</td>
                                <td>{{ $job->attempts }}</td>
                                <td>{{ date('Y-m-d H:i:s', $job->available_at) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No pending jobs</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Failed Jobs -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Failed Jobs ({{ $failedJobs->count() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Queue</th>
                            <th>Failed At</th>
                            <th>Exception</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($failedJobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->queue }}</td>
                                <td>{{ $job->failed_at }}</td>
                                <td class="small">
                                    <div style="max-height: 100px; overflow-y: auto;">
                                        {{ Str::limit($job->exception, 200) }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No failed jobs</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
