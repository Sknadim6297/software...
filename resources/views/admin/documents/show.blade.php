@extends('admin.layouts.app')

@section('title', 'Employee Documents')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employees.show', $employee->id) }}">{{ $employee->name }}</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Documents</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                @if($employee->profile_image)
                    <img src="{{ asset('storage/' . $employee->profile_image) }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;" alt="{{ $employee->name }}">
                @else
                    <img src="{{ asset('template/images/profile/17.jpg') }}" class="rounded-circle mb-3" width="100" height="100" alt="{{ $employee->name }}">
                @endif
                <h4 class="mb-1">{{ $employee->name }}</h4>
                <p class="text-muted">{{ $employee->employee_code }}</p>
                <span class="badge 
                    @if($employee->status == 'active') badge-success
                    @elseif($employee->status == 'inactive') badge-warning
                    @else badge-danger
                    @endif
                ">
                    {{ ucfirst($employee->status) }}
                </span>
                <hr>
                <div class="mt-3">
                    <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-secondary btn-block">
                        <i class="fa fa-arrow-left me-2"></i>Back to Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">Upload New Document</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.documents.upload', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="aadhaar_card">Aadhaar Card</option>
                            <option value="pan_card">PAN Card</option>
                            <option value="10th_admit_card">10th Admit Card</option>
                            <option value="12th_marksheet">12th Marksheet</option>
                            <option value="graduation_certificate">Graduation Certificate</option>
                            <option value="last_company_offer_letter">Last Company Offer Letter</option>
                            <option value="salary_slip">Salary Slip</option>
                            <option value="reference_contact">Reference Contact</option>
                        </select>
                        @error('document_type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Select File <span class="text-danger">*</span></label>
                        <input type="file" name="document_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                        @error('document_file')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-upload me-2"></i>Upload Document
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Employee Documents ({{ $employee->documents->count() }})</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Document Type</th>
                                <th>File Name</th>
                                <th>Uploaded Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->documents as $document)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</strong>
                                    </td>
                                    <td>{{ $document->original_filename }}</td>
                                    <td>{{ $document->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <circle fill="#000000" cx="5" cy="12" r="2"/>
                                                        <circle fill="#000000" cx="12" cy="12" r="2"/>
                                                        <circle fill="#000000" cx="19" cy="12" r="2"/>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('admin.documents.download', $document->id) }}">
                                                    <i class="fa fa-download me-2"></i>Download
                                                </a>
                                                <a class="dropdown-item" href="{{ asset('storage/' . $document->file_path) }}" target="_blank">
                                                    <i class="fa fa-eye me-2"></i>View
                                                </a>
                                                <form action="{{ route('admin.documents.destroy', $document->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this document?')">
                                                        <i class="fa fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No documents uploaded yet. Use the form to upload documents.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employee->documents->count() > 0)
                    <div class="mt-4">
                        <h5>Document Checklist</h5>
                        <div class="row">
                            @php
                                $requiredDocs = [
                                    'aadhaar_card' => 'Aadhaar Card',
                                    'pan_card' => 'PAN Card',
                                    '10th_admit_card' => '10th Admit Card',
                                    '12th_marksheet' => '12th Marksheet',
                                    'graduation_certificate' => 'Graduation Certificate',
                                ];
                                $uploadedTypes = $employee->documents->pluck('document_type')->toArray();
                            @endphp
                            @foreach($requiredDocs as $type => $label)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ in_array($type, $uploadedTypes) ? 'checked' : '' }} disabled>
                                        <label class="form-check-label {{ in_array($type, $uploadedTypes) ? 'text-success' : 'text-muted' }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
