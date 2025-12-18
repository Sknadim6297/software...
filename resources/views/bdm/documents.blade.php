@extends('layouts.app')

@section('title', 'Documents')
@section('page-title', 'Documents')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-file-alt"></i> My Documents
    </div>
    <div class="card-body">
        @if(count($missingTypes) > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> You have <strong>{{ count($missingTypes) }}</strong> missing document(s). Please upload all required documents.
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Document Type</th>
                        <th>Status</th>
                        <th>Uploaded On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documentTypes as $key => $label)
                        @php
                            $document = $documents->firstWhere('document_type', $key);
                        @endphp
                        <tr>
                            <td>
                                <i class="fas fa-file-pdf text-danger"></i> {{ $label }}
                            </td>
                            <td>
                                @if($document)
                                    <span class="badge bg-success">Uploaded</span>
                                @else
                                    <span class="badge bg-warning">Missing</span>
                                @endif
                            </td>
                            <td>
                                {{ $document ? $document->uploaded_at->format('M d, Y') : '-' }}
                            </td>
                            <td>
                                @if($document)
                                    <a href="{{ route('bdm.documents.download', $document->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Delete this document?')) document.getElementById('delete-{{ $document->id }}').submit();">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    <form id="delete-{{ $document->id }}" action="{{ route('bdm.documents.delete', $document->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal-{{ $key }}">
                                    <i class="fas fa-upload"></i> {{ $document ? 'Replace' : 'Upload' }}
                                </button>
                                
                                <!-- Upload Modal -->
                                <div class="modal fade" id="uploadModal-{{ $key }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('bdm.documents.upload') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Upload {{ $label }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="document_type" value="{{ $key }}">
                                                    <div class="mb-3">
                                                        <label class="form-label">Select File (PDF, Image - Max 5MB)</label>
                                                        <input type="file" name="document_file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
