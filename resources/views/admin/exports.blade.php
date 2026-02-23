@extends('layout')

@section('content')
<div class="container py-5">

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1"><i class="bi bi-folder2-open text-danger me-2"></i>Exported PDFs</h1>
            <p class="text-muted mb-0 small">All PDF files generated from batch post exports</p>
        </div>
        <a href="{{ route('admin.posts') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Posts
        </a>
    </div>

    {{-- File list --}}
    @if($files->isEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-pdf display-4 text-muted"></i>
                <h5 class="mt-3 text-muted">No exports yet</h5>
                <p class="text-muted small">Go to the <a href="{{ route('admin.posts') }}">Posts page</a>, select posts and click <strong>Export Selected</strong> to generate a PDF.</p>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4"><i class="bi bi-file-pdf text-danger me-1"></i> File</th>
                            <th>Size</th>
                            <th>Generated</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $i => $file)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-danger-subtle text-danger rounded-2 px-2 py-1">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold small text-break" style="max-width: 380px;">
                                            {{ $file['name'] }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.72rem;">PDF Document</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted small">
                                {{ number_format($file['size'] / 1024, 1) }} KB
                            </td>
                            <td class="text-muted small">
                                <i class="bi bi-clock me-1"></i>{{ $file['last_modified'] }}
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ $file['download_url'] }}"
                                   class="btn btn-sm btn-success px-3"
                                   title="Download {{ $file['name'] }}">
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted small bg-light border-0 px-4 py-2">
                {{ $files->count() }} file(s) found
            </div>
        </div>
    @endif

</div>
@endsection
