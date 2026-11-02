```php
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">My Documents</h3>
            <p class="text-muted mb-0">Upload and manage your employee documents.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Upload New Document</h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('employee.documents.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Document Type</label>
                        <select name="document_type" class="form-select" required>
                            <option value="">Select document type</option>
                            <option value="Aadhaar Card">Aadhaar Card</option>
                            <option value="PAN Card">PAN Card</option>
                            <option value="Education Certificate">Education Certificate</option>
                            <option value="Experience Letter">Experience Letter</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Choose File</label>
                        <input type="file"
                               name="document"
                               class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png"
                               required>
                        <small class="text-muted">PDF, JPG, PNG — maximum 5 MB.</small>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit"
                                class="btn btn-primary w-100"
                                style="background:#394174;border-color:#394174;">
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Uploaded Documents</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Document Type</th>
                            <th>File Name</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Admin Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td class="ps-4">{{ $loop->iteration }}</td>

                                <td>{{ $document->document_type }}</td>

                                <td>{{ $document->original_name }}</td>

                                <td>{{ $document->created_at->format('d M Y') }}</td>

                                <td>
                                    @php
                                        $badgeClass = match($document->status) {
                                            'verified' => 'success',
                                            'rejected' => 'danger',
                                            default => 'warning',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $badgeClass }}">
                                        {{ ucfirst($document->status) }}
                                    </span>
                                </td>

                                <td>{{ $document->admin_remarks ?: '-' }}</td>

                                <td>
                                    <a href="{{ route('employee.documents.download', $document) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Download
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    No documents uploaded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
```
