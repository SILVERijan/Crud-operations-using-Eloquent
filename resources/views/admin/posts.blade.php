@extends('layout')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>All Posts</h1>
        <div class="d-flex gap-3 align-items-center">
            <button id="exportSelected" class="btn btn-outline-danger" style="display: none;">
                <i class="bi bi-file-pdf"></i> Export Selected (<span id="selectedCount">0</span>)
            </button>
            <a href="{{ route('admin.exports') }}" class="btn btn-outline-secondary">
                <i class="bi bi-folder2-open me-1"></i> View Exports
            </a>
            <form method="GET" class="d-flex gap-2">
                <select name="user_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Batch Progress Area -->
    <div id="batchProgress" class="card shadow-sm mb-4" style="display: none;">
        <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Export Progress</span>
                <span id="batchStatus" class="badge bg-primary">Processing...</span>
            </h5>
            <div class="progress mt-3" style="height: 22px; border-radius: 8px;">
                <div id="progressBar"
                     class="progress-bar progress-bar-striped progress-bar-animated"
                     role="progressbar"
                     style="width: 0%; transition: width 0.4s ease;">0%</div>
            </div>
            <p id="progressDetail" class="text-muted small mt-2 mb-0"></p>
        </div>
    </div>

    <!-- Export Complete Banner -->
    <div id="exportCompleteBanner" class="alert alert-success d-flex align-items-center gap-3 mb-4 shadow-sm" style="display: none !important; border-radius: 10px;">
        <i class="bi bi-check-circle-fill fs-3"></i>
        <div class="flex-grow-1">
            <strong>Export Complete!</strong>
            <p class="mb-0 text-success-emphasis small">Your PDF has been generated successfully.</p>
        </div>
        <a id="downloadLink" href="#" class="btn btn-success btn-sm px-4 fw-semibold">
            <i class="bi bi-download me-1"></i> Download PDF
        </a>
    </div>

    <!-- Hidden iframe for triggering download without navigation -->
    <iframe id="downloadFrame" style="display:none;"></iframe>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="40"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td><input type="checkbox" name="post_ids[]" value="{{ $post->id }}" class="form-check-input post-checkbox"></td>
                            <td>{{ $post->id }}</td>
                            <td>{{ Str::limit($post->title, 50) }}</td>
                            <td>{{ $post->user?->name ?? 'Unknown' }}</td>
                            <td>{{ $post->categories->pluck('name')->implode(', ') ?: 'N/A' }}</td>
                            <td>{{ $post->published_at?->format('M d, Y') ?? 'Not published' }}</td>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No posts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll          = document.getElementById('selectAll');
    const checkboxes         = document.querySelectorAll('.post-checkbox');
    const exportBtn          = document.getElementById('exportSelected');
    const selectedCount      = document.getElementById('selectedCount');
    const batchProgress      = document.getElementById('batchProgress');
    const batchStatus        = document.getElementById('batchStatus');
    const progressBar        = document.getElementById('progressBar');
    const progressDetail     = document.getElementById('progressDetail');
    const completeBanner     = document.getElementById('exportCompleteBanner');
    const downloadLink       = document.getElementById('downloadLink');
    const downloadFrame      = document.getElementById('downloadFrame');

    // ── checkbox helpers ────────────────────────────────────────────
    function updateExportBtn() {
        const count = document.querySelectorAll('.post-checkbox:checked').length;
        selectedCount.textContent = count;
        exportBtn.style.display = count > 0 ? 'inline-block' : 'none';
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateExportBtn();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateExportBtn));

    // ── export button ────────────────────────────────────────────────
    exportBtn.addEventListener('click', function () {
        const selectedIds = Array.from(document.querySelectorAll('.post-checkbox:checked'))
            .map(cb => cb.value);

        if (selectedIds.length === 0) return;

        // Reset UI
        exportBtn.disabled = true;
        completeBanner.style.display = 'none';
        batchProgress.style.display  = 'block';
        batchStatus.textContent      = 'Starting export…';
        batchStatus.className        = 'badge bg-primary';
        progressBar.style.width      = '0%';
        progressBar.textContent      = '0%';
        progressBar.className        = 'progress-bar progress-bar-striped progress-bar-animated';
        progressDetail.textContent   = '';

        fetch("{{ route('admin.posts.export') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ post_ids: selectedIds })
        })
        .then(res => {
            if (!res.ok) throw new Error('Export failed to start');
            return res.json();
        })
        .then(data => {
            if (data.batch_id) {
                pollBatch(data.batch_id, data.download_route);
            }
        })
        .catch(err => {
            batchStatus.textContent = 'Error: ' + err.message;
            batchStatus.className   = 'badge bg-danger';
            exportBtn.disabled      = false;
        });
    });

    // ── polling ──────────────────────────────────────────────────────
    function pollBatch(batchId, downloadRoute) {
        const interval = setInterval(() => {
            fetch(`{{ route('admin.batch.status', ['batchId' => '__ID__']) }}`.replace('__ID__', batchId))
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch status');
                    return res.json();
                })
                .then(batch => {
                    const pct = batch.progress ?? 0;
                    progressBar.style.width  = `${pct}%`;
                    progressBar.textContent  = `${pct}%`;
                    progressDetail.textContent = `${batch.processedJobs} of ${batch.totalJobs} job(s) processed`;
                    batchStatus.textContent  = `Processing (${batch.processedJobs}/${batch.totalJobs})…`;

                    if (batch.failedJobs > 0 && !batch.finished_at) {
                        batchStatus.textContent = `⚠ ${batch.failedJobs} job(s) failed`;
                        batchStatus.className   = 'badge bg-warning text-dark';
                    }

                    // ── finished ─────────────────────────────────────
                    if (batch.finished_at) {
                        clearInterval(interval);

                        // Snap progress to 100 %
                        progressBar.style.width = '100%';
                        progressBar.textContent = '100%';
                        progressBar.className   = 'progress-bar bg-success';

                        if (batch.failedJobs > 0 && batch.failedJobs === batch.totalJobs) {
                            // All jobs failed
                            batchStatus.textContent = 'Export Failed';
                            batchStatus.className   = 'badge bg-danger';
                            progressBar.className   = 'progress-bar bg-danger';
                            progressDetail.textContent = 'All jobs failed. Please try again.';
                        } else {
                            // Success (partial or full)
                            batchStatus.textContent  = '✓ Completed!';
                            batchStatus.className    = 'badge bg-success';
                            progressDetail.textContent = 'PDF ready — see banner below.';

                            // Show completion banner
                            completeBanner.style.removeProperty('display');
                            completeBanner.style.display = 'flex';

                            // Set download link
                            downloadLink.href = downloadRoute;

                            // Trigger automatic download via hidden iframe
                            downloadFrame.src = downloadRoute;
                        }

                        exportBtn.disabled = false;
                    }

                    // ── cancelled ─────────────────────────────────────
                    if (batch.cancelled_at) {
                        clearInterval(interval);
                        batchStatus.textContent    = 'Cancelled';
                        batchStatus.className      = 'badge bg-secondary';
                        progressDetail.textContent = 'Export was cancelled.';
                        exportBtn.disabled         = false;
                    }
                })
                .catch(err => {
                    clearInterval(interval);
                    batchStatus.textContent  = 'Polling error';
                    batchStatus.className    = 'badge bg-danger';
                    exportBtn.disabled       = false;
                });
        }, 2000);
    }
});
</script>
@endpush
@endsection
